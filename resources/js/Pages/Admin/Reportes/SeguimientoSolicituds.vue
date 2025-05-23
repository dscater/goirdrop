<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useAxios } from "@/composables/axios/useAxios";

const { setLoading } = useApp();
const { axiosGet } = useAxios();
const cargarListas = () => {};
const obtenerFechaActual = () => {
    const fecha = new Date();
    const anio = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Mes empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, "0"); // Día del mes
    return `${anio}-${mes}-${dia}`;
};

const form = ref({
    formato: "pdf",
    estado: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listFormato = ref([
    { value: "pdf", label: "PDF" },
    { value: "excel", label: "EXCEL" },
]);

const listCategorias = ref([]);
const listEstados = ref([
    { value: "todos", label: "TODOS" },
    { value: "PENDIENTE", label: "Pendiente" },
    { value: "EN PROCESO", label: "En proceso" },
    { value: "EN ALMACÉN", label: "En almacén" },
    { value: "ENTREGADO", label: "Entregado" },
]);

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_seguimiento_solicituds", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const cargarCategorias = async () => {
    const resp = await axiosGet(route("categorias.listado"));
    listCategorias.value = resp.categorias;
    listCategorias.value.unshift({ id: "todos", nombre: "TODOS" });
};

onMounted(() => {
    // cargarCategorias();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Reporte de Seguimiento de Solicitud de Productos"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">
            Reportes > de Seguimiento de Solicitud de Productos
        </li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">
        Reportes > Seguimiento de Solicitud de Productos
    </h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Fecha inicio*</label>
                                        <input
                                            type="date"
                                            v-model="form.fecha_ini"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <label>Fecha final*</label>
                                        <input
                                            type="date"
                                            v-model="form.fecha_fin"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Estado de seguimiento*</label>
                                <select
                                    v-model="form.estado"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listEstados"
                                        :value="item.value"
                                    >
                                        {{ item.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Seleccionar formato*</label>
                                <select
                                    :hide-details="
                                        form.errors?.formato ? false : true
                                    "
                                    :error="form.errors?.formato ? true : false"
                                    :error-messages="
                                        form.errors?.formato
                                            ? form.errors?.formato
                                            : ''
                                    "
                                    v-model="form.formato"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listFormato"
                                        :value="item.value"
                                    >
                                        {{ item.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
