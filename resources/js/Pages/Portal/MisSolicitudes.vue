<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { onMounted, ref } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { useAxios } from "@/composables/axios/useAxios";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import MiPaginacion from "@/Components/MiPaginacion.vue";
import infoOrdenVenta from "@/Components/infoOrdenVenta.vue";
import { useFormater } from "@/composables/useFormater";
const { getFormatoMoneda } = useFormater();
const { oConfiguracion } = useConfiguracion();
const { props: props_page } = usePage();
const { auth } = props_page;
const { axiosGet } = useAxios();
const nroTab = ref(1);

const listOrdenesVenta = ref([]);
const paramsOrdenesVenta = ref({
    perPage: 9,
    page: 1,
    search: "",
    orderByCol: "id",
    desc: "desc",
});
const paginacionOrdenVentas = ref({
    totalData: 0,
    perPage: paramsOrdenesVenta.value.perPage,
    currentPage: paramsOrdenesVenta.value.page,
    lastPage: 0,
});
const obtenerOrdenesVenta = async () => {
    const data = await axiosGet(
        route("orden_ventas.ordenesCliente"),
        paramsOrdenesVenta.value
    );
    listOrdenesVenta.value = data.ordenVentas;
    paginacionOrdenVentas.value.totalData = data.total;
    paginacionOrdenVentas.value.currentPage = paramsOrdenesVenta.value.page;
    paginacionOrdenVentas.value.lastPage = data.lastPage;
};

const updatePageOrdenVenta = (value) => {
    paramsOrdenesVenta.value.page = value;
    if (paramsOrdenesVenta.value.page < 0) paramsOrdenesVenta.value.page = 1;
    if (paramsOrdenesVenta.value.page > paginacionOrdenVentas.value.totalData)
        paramsOrdenesVenta.value.page = paginacionOrdenVentas.value.lastPage;
    obtenerOrdenesVenta();
};
const modalInfoOrdenVenta = ref(false);
const oOrdenVenta = ref(null);
const verInfoOrdenVenta = (item) => {
    oOrdenVenta.value = item;
    modalInfoOrdenVenta.value = true;
};
const cargarListas = () => {
    obtenerOrdenesVenta();
};

const verificaInicioSesion = () => {
    if (!auth.user) {
        modalLogin.value = true;
        return null;
    }
    return auth;
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <infoOrdenVenta
        :orden-venta="oOrdenVenta"
        :open_dialog="modalInfoOrdenVenta"
        @cerrar-dialog="modalInfoOrdenVenta = false"
    ></infoOrdenVenta>
    <!-- BEGIN #productos -->
    <div id="misSolicitudes" class="productos pt-5 pb-5 mb-5">
        <!-- BEGIN container -->
        <div class="container mb-0">
            <h4 class="section-title clearfix">
                <span class="flex-1">
                    Mis compras
                    <small
                        >Realiza el seguimiento de tus compras</small
                    >
                </span>
            </h4>
            <div class="row">
                <div class="col-12">
                    <div class="contenedor_nav">
                        <!-- COMPRAS -->
                        <div class="contenedor-nav bg-white">
                            <div class="row">
                                <div
                                    class="col-md-4 mb-2"
                                    v-for="item in listOrdenesVenta"
                                >
                                    <div class="card">
                                        <div class="card-body cont_orden">
                                            <small
                                                class="font-weight-bold cod_orden"
                                                >{{ item.codigo }}</small
                                            >
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    Fecha:
                                                </div>
                                                <div class="col-8 text-left">
                                                    {{ item.fecha_orden_t }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    Total:
                                                </div>
                                                <div class="col-8 text-left">
                                                    {{
                                                        oConfiguracion
                                                            ?.conf_moneda?.abrev
                                                    }}
                                                    {{ getFormatoMoneda(item.total) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    Estado:
                                                </div>
                                                <div class="col-8 text-left">
                                                    <span
                                                        class="badge"
                                                        :class="{
                                                            'bg-secondary':
                                                                item.estado_orden ==
                                                                'PENDIENTE',
                                                            'bg-success':
                                                                item.estado_orden ==
                                                                'CONFIRMADO',
                                                            'bg-danger':
                                                                item.estado_orden ==
                                                                'RECHAZADO',
                                                        }"
                                                        >{{
                                                            item.estado_orden
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="row">
                                                <div
                                                    class="col-md-12 d-flex justify-content-end"
                                                >
                                                    <button
                                                        type="button"
                                                        class="btn bg-dark text-white btn-sm"
                                                        @click.prevent="
                                                            verInfoOrdenVenta(
                                                                item
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa fa-list"
                                                        ></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <MiPaginacion
                                        class="justify-content-center mb-0"
                                        :totalData="
                                            paginacionOrdenVentas.totalData
                                        "
                                        :currentPage="
                                            paginacionOrdenVentas.currentPage
                                        "
                                        :perPage="paginacionOrdenVentas.perPage"
                                        @updatePage="updatePageOrdenVenta"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END container -->
    </div>
    <!-- END #productos -->
</template>

<style scoped>
#misSolicitudes {
    min-height: 62vh;
}
.contenedor_nav {
    width: 100%;
}
.contenedor_nav .navs {
    display: flex;
}
.contenedor_nav .navs .item-nav {
    cursor: pointer;
    background-color: rgb(207, 207, 207);
    padding: 10px;
    color: rgb(129, 129, 129);
}

.contenedor_nav .contenedor-nav {
    padding: 10px;
}

.contenedor_nav .navs .item-nav.active {
    cursor: auto;
    background-color: var(--principal-portal);
    color: white;
}

/* ORDENES y SOLICITUDES */
.cont_orden {
    position: relative;
    padding-top: 19px;
    font-size: 0.86rem;
}

.cod_solicitud,
.cod_orden {
    position: absolute;
    top: 0;
    right: 0;
    padding: 1px;
    border-radius: 0px 0px 0px 10px;
}
.cod_orden {
    background-color: var(--principal-portal2);
    color: white;
}
.cod_solicitud {
    background-color: var(--bg4);
    color: white;
}
</style>
