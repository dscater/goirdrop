<script setup>
import App from "@/Layouts/App.vue";
defineOptions({
    layout: App,
});
import { onMounted, ref, nextTick } from "vue";
import { useApp } from "@/composables/useApp";
// componentes
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { usePage, Head, Link } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import accessibility from "highcharts/modules/accessibility";
exporting(Highcharts);
accessibility(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});

const props_page = defineProps({
    array_infos: {
        type: Array,
    },
});

const { setLoading } = useApp();

const obtenerFechaActual = () => {
    const fecha = new Date();
    const anio = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Mes empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, "0"); // Día del mes
    return `${anio}-${mes}-${dia}`;
};

// GRAFICO 1
const listEstados = ref([
    { value: "todos", label: "TODOS" },
    { value: "PENDIENTE", label: "PENDIENTE" },
    { value: "RECHAZADO", label: "RECHAZADO" },
    { value: "CONFIRMADO", label: "CONFIRMADO" },
]);
const grafico1 = ref({
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
    estado: "todos",
});

const generarG1 = () => {
    axios
        .get(route("reportes.r_g_orden_ventas"), { params: grafico1.value })
        .then((response) => {
            const data = response.data.data;
            const categories = response.data.categories;
            nextTick(() => {
                Highcharts.chart("container1", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: `ORDENES DE VENTA`,
                    },
                    subtitle: {
                        align: "center",
                        text: ``,
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        categories: categories,
                    },
                    yAxis: {
                        title: {
                            text: "CANTIDAD",
                        },
                    },
                    legend: {
                        enabled: true,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                // format: "{point.y}",
                                style: {
                                    fontSize: "11px",
                                    fontWeight: "bold",
                                },
                                formatter: function () {
                                    return parseInt(this.point.y); // Aquí se aplica el formato de moneda
                                },
                            },
                        },
                    },
                    tooltip: {
                        useHTML: true,
                        formatter: function () {
                            // console.log(this.point.ordenVentas);

                            let trTbody = ``;
                            this.point.ordenVentas.forEach((elem) => {
                                elem.detalle_venta.forEach((elemDetalle) => {
                                    trTbody += `<tr>`;
                                    trTbody += `<td class="border p-1">${elem.codigo}</td>`;
                                    trTbody += `<td class="border p-1">${elemDetalle.producto.nombre}</td>`;
                                    trTbody += `<td class="border p-1">${elemDetalle.cantidad}</td>`;
                                    trTbody += `<td class="border p-1">${elem.cliente.full_name}</td>`;
                                    trTbody += `</tr>`;
                                });
                            });

                            return `<h4 style="font-size:13px" class="w-100 text-center mb-1">${this.x}</h4><br>
                <table class="border">
                    <thead>
                        <tr>
                            <th class="border p-1">Cód.</th>
                            <th class="border p-1">Prod.</th>
                            <th class="border p-1">Cant.</th>
                            <th class="border p-1">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${trTbody}
                    </tbody>
                </table>`;
                        },
                    },

                    series: [
                        {
                            name: "Ordenes de venta",
                            data: data,
                            colorByPoint: true,
                        },
                    ],
                });
            });
        });
};

// GRAFICO 2
const grafico2 = ref({
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
    estado: "todos",
});

const listEstados2 = ref([
    { value: "todos", label: "TODOS" },
    { value: "PENDIENTE", label: "PENDIENTE" },
    { value: "RECHAZADO", label: "RECHAZADO" },
    { value: "CONFIRMADO", label: "CONFIRMADO" },
]);

const generarG2 = () => {
    axios
        .get(route("reportes.r_g_solicitud_productos"), {
            params: grafico2.value,
        })
        .then((response) => {
            const data = response.data.data;
            const categories = response.data.categories;
            nextTick(() => {
                Highcharts.chart("container2", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: `SOLICITUD DE COMPRA DE PRODUCTOS`,
                    },
                    subtitle: {
                        align: "center",
                        text: ``,
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        categories: categories,
                    },
                    yAxis: {
                        title: {
                            text: "CANTIDAD",
                        },
                    },
                    legend: {
                        enabled: true,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                // format: "{point.y}",
                                style: {
                                    fontSize: "11px",
                                    fontWeight: "bold",
                                },
                                formatter: function () {
                                    return parseInt(this.point.y); // Aquí se aplica el formato de moneda
                                },
                            },
                        },
                    },
                    tooltip: {
                        useHTML: true,
                        formatter: function () {
                            // console.log(this.point.solicitudProductos);

                            let trTbody = ``;
                            this.point.solicitudProductos.forEach((elem) => {
                                elem.solicitud_detalles.forEach(
                                    (elemDetalle) => {
                                        trTbody += `<tr>`;
                                        trTbody += `<td class="border p-1">${elem.codigo_solicitud}</td>`;
                                        trTbody += `<td class="border p-1">${elemDetalle.nombre_producto}</td>`;
                                        trTbody += `<td class="border p-1">1</td>`;
                                        trTbody += `<td class="border p-1">${elem.cliente.full_name}</td>`;
                                        trTbody += `</tr>`;
                                    }
                                );
                            });

                            return `<h4 style="font-size:13px" class="w-100 text-center mb-0">${this.x}</h4><br>
                <table class="border">
                    <thead>
                        <tr>
                            <th class="border p-1">Cód.</th>
                            <th class="border p-1">Prod.</th>
                            <th class="border p-1">Cant.</th>
                            <th class="border p-1">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${trTbody}
                    </tbody>
                </table>`;
                        },
                    },

                    series: [
                        {
                            name: "Solicitud de productos",
                            data: data,
                            colorByPoint: true,
                        },
                    ],
                });
            });
        });
};

// GRAFICO 3
const grafico3 = ref({
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
    estado: "todos",
});

const listEstados3 = ref([
    { value: "todos", label: "TODOS" },
    { value: "PENDIENTE", label: "PENDIENTE" },
    { value: "EN PROCESO", label: "EN PROCESO" },
    { value: "EN ALMACÉN", label: "EN ALMACÉN" },
    { value: "ENTREGADO", label: "ENTREGADO" },
]);
const generarG3 = () => {
    axios
        .get(route("reportes.r_g_seguimiento_productos"), {
            params: grafico3.value,
        })
        .then((response) => {
            const data = response.data.data;
            const categories = response.data.categories;
            nextTick(() => {
                Highcharts.chart("container3", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: `SEGUIMIENTO DE SOLICITUD DE COMPRA DE PRODUCTOS`,
                    },
                    subtitle: {
                        align: "center",
                        text: ``,
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        categories: categories,
                    },
                    yAxis: {
                        title: {
                            text: "CANTIDAD",
                        },
                    },
                    legend: {
                        enabled: true,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                // format: "{point.y}",
                                style: {
                                    fontSize: "11px",
                                    fontWeight: "bold",
                                },
                                formatter: function () {
                                    return parseInt(this.point.y); // Aquí se aplica el formato de moneda
                                },
                            },
                        },
                    },
                    tooltip: {
                        useHTML: true,
                        formatter: function () {
                            // console.log(this.point.solicitudProductos);

                            let trTbody = ``;
                            this.point.solicitudProductos.forEach((elem) => {
                                elem.solicitud_detalles.forEach(
                                    (elemDetalle) => {
                                        trTbody += `<tr>`;
                                        trTbody += `<td class="border p-1">${elem.codigo_solicitud}</td>`;
                                        trTbody += `<td class="border p-1">${elemDetalle.nombre_producto}</td>`;
                                        trTbody += `<td class="border p-1">1</td>`;
                                        trTbody += `<td class="border p-1">${elem.cliente.full_name}</td>`;
                                        trTbody += `</tr>`;
                                    }
                                );
                            });

                            return `<h4 style="font-size:13px" class="w-100 text-center mb-1">${this.x}</h4><br>
                <table class="border">
                    <thead>
                        <tr>
                            <th class="border p-1">Cód.</th>
                            <th class="border p-1">Prod.</th>
                            <th class="border p-1">Cant.</th>
                            <th class="border p-1">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${trTbody}
                    </tbody>
                </table>`;
                        },
                    },

                    series: [
                        {
                            name: "Seguimiento Solicitud Producto",
                            data: data,
                            colorByPoint: true,
                        },
                    ],
                });
            });
        });
};

const { oConfiguracion } = useConfiguracion();

const { props } = usePage();

onMounted(() => {
    generarG1();
    generarG2();
    generarG3();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Inicio"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <!-- <h1 class="page-header">Panel</h1> -->
    <!-- END page-header -->
    <div class="row">
        <div class="col-12">
            <h4 class="text-center text-h4">
                Bienvenid@ {{ props.auth.user.full_name }}
            </h4>
        </div>
    </div>
    <div class="row">
        <!-- BEGIN col-3 -->
        <div class="col-xl-3 col-md-6" v-for="item in array_infos">
            <div class="widget widget-stats" :class="[item.color]">
                <div class="stats-icon">
                    <i class="fa" :class="[item.icon]"></i>
                </div>
                <div class="stats-info text-white">
                    <h4>{{ item.label }}</h4>
                    <p>{{ item.cantidad }}</p>
                </div>
                <div class="stats-link">
                    <Link :href="route(item.url)"
                        >Ver más <i class="fa fa-arrow-alt-circle-right"></i
                    ></Link>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Ordenes de ventas</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Rango de fechas</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico1.fecha_ini"
                                        @keyup="generarG1"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico1.fecha_fin"
                                        @keyup="generarG1"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Estado</label>
                            <select
                                name=""
                                id=""
                                class="form-select"
                                v-model="grafico1.estado"
                                @change="generarG1"
                            >
                                <option
                                    v-for="item in listEstados"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="container1"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Solicitud de productos</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Rango de fechas</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico2.fecha_ini"
                                        @keyup="generarG2"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico2.fecha_fin"
                                        @keyup="generarG2"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Estado</label>
                            <select
                                name=""
                                id=""
                                class="form-select"
                                v-model="grafico2.estado"
                                @change="generarG2"
                            >
                                <option
                                    v-for="item in listEstados2"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="container2"></div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Seguimiento de solicitud de productos</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Rango de fechas</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico3.fecha_ini"
                                        @keyup="generarG3"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="grafico3.fecha_fin"
                                        @keyup="generarG3"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Estado</label>
                            <select
                                name=""
                                id=""
                                class="form-select"
                                v-model="grafico3.estado"
                                @change="generarG3"
                            >
                                <option
                                    v-for="item in listEstados3"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="container3"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.item_btn {
    margin: 10px;
}

.contenido_item i {
    color: black;
}

.contenido_item {
    transition: all 0.8s;
    color: black;
    padding: 10px;
    cursor: pointer;
    background-color: rgb(248, 229, 229);
    border: solid 2px rgb(243, 211, 211);
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 1.3em;
    flex-direction: column;
}
</style>
