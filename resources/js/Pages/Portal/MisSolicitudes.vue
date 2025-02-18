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
import SolicitudProducto from "@/Components/SolicitudProducto.vue";
import infoOrdenVenta from "@/Components/infoOrdenVenta.vue";
import InfoSolicitudProducto from "@/Components/InfoSolicitudProducto.vue";
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
const listSolicitudProductos = ref([]);
const paramsSolicitudProductos = ref({
    perPage: 9,
    page: 1,
    search: "",
    orderByCol: "id",
    desc: "desc",
});
const paginacionSolicitudProductos = ref({
    totalData: 0,
    perPage: paramsSolicitudProductos.value.perPage,
    currentPage: paramsSolicitudProductos.value.page,
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

const obtenerSolicitudProductos = async () => {
    const data = await axiosGet(
        route("solicitud_productos.solicitudProductosCliente"),
        paramsSolicitudProductos.value
    );
    listSolicitudProductos.value = data.solicitudProductos;
    paginacionSolicitudProductos.value.totalData = data.total;
    paginacionSolicitudProductos.value.currentPage =
        paramsSolicitudProductos.value.page;
    paginacionSolicitudProductos.value.lastPage = data.lastPage;
};

const updatePageSolicitudProducto = (value) => {
    paramsSolicitudProductos.value.page = value;
    if (paramsSolicitudProductos.value.page < 0)
        paramsSolicitudProductos.value.page = 1;
    if (
        paramsSolicitudProductos.value.page >
        paginacionSolicitudProductos.value.totalData
    )
        paramsSolicitudProductos.value.page =
            paginacionSolicitudProductos.value.lastPage;
    obtenerSolicitudProductos();
};

const modalSolicitudProducto = ref(false);
const nuevaSolicitudProducto = () => {
    if (verificaInicioSesion()) {
        modalSolicitudProducto.value = true;
    }
};

const modalInfoSolicitudProducto = ref(false);
const oSolicitudProducto = ref(null);
const verInfoSolicitud = (item) => {
    oSolicitudProducto.value = item;
    modalInfoSolicitudProducto.value = true;
};

const cargarListas = () => {
    obtenerOrdenesVenta();
    obtenerSolicitudProductos();
};

const irTab = (value) => {
    nroTab.value = value;
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
    <InfoSolicitudProducto
        :solicitudProducto="oSolicitudProducto"
        :open_dialog="modalInfoSolicitudProducto"
        @cerrar-dialog="modalInfoSolicitudProducto = false"
    ></InfoSolicitudProducto>

    <SolicitudProducto
        :open_dialog="modalSolicitudProducto"
        @cerrar-dialog="modalSolicitudProducto = false"
        @envio-formulario="obtenerSolicitudProductos"
    ></SolicitudProducto>

    <!-- BEGIN #productos -->
    <div id="misSolicitudes" class="productos pt-5 pb-5 mb-5">
        <!-- BEGIN container -->
        <div class="container mb-0">
            <h4 class="section-title clearfix">
                <span class="flex-1">
                    Mis solicitudes
                    <small
                        >Realiza el seguimiento de tus compras y
                        solicitudes</small
                    >
                </span>
            </h4>
            <div class="row">
                <div class="col-12">
                    <div class="contenedor_nav">
                        <div class="navs">
                            <div
                                class="item-nav"
                                :class="[nroTab == 1 ? 'active' : '']"
                                @click="irTab(1)"
                            >
                                Mis compras
                            </div>
                            <div
                                class="item-nav"
                                :class="[nroTab == 2 ? 'active' : '']"
                                @click.stop="irTab(2)"
                            >
                                <span>Solicitud de productos</span>
                                <button
                                    class="btn btn-white ml-1 btn-sm"
                                    @click.stop="nuevaSolicitudProducto"
                                >
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- COMPRAS -->
                        <div class="contenedor-nav bg-white" v-if="nroTab == 1">
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
                                                    {{ item.total }}
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
                        <!-- SOLICITUD PRODUCTOS -->
                        <div class="contenedor-nav bg-white" v-if="nroTab == 2">
                            <div class="row">
                                <div
                                    v-for="item in listSolicitudProductos"
                                    class="col-md-4 mb-2"
                                >
                                    <div class="card">
                                        <div class="card-body cont_orden">
                                            <small
                                                class="font-weight-bold cod_solicitud"
                                                >{{
                                                    item.codigo_solicitud
                                                }}</small
                                            >
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    Fecha:
                                                </div>
                                                <div class="col-8 text-left">
                                                    {{ item.fecha_solicitud_t }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    Dpto.:
                                                </div>
                                                <div class="col-8 text-left">
                                                    {{ item?.sede.nombre }}
                                                </div>
                                            </div>
                                            <div class="row my-2">
                                                <div class="col-4 text-right">
                                                    Solicitud:
                                                </div>
                                                <div class="col-8 text-left">
                                                    <span
                                                        class="badge"
                                                        :class="{
                                                            'bg-secondary':
                                                                item.estado_solicitud ==
                                                                'PENDIENTE',
                                                            'bg-success':
                                                                item.estado_solicitud ==
                                                                'APROBADO',
                                                            'bg-danger':
                                                                item.estado_solicitud ==
                                                                'RECHAZADO',
                                                        }"
                                                        >{{
                                                            item.estado_solicitud
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                            <div class="row my-2">
                                                <div class="col-4 text-right">
                                                    Estado Entrega:
                                                </div>
                                                <div class="col-8 text-left">
                                                    <span
                                                        class="badge"
                                                        :class="{
                                                            'bg-secondary':
                                                                item.estado_seguimiento ==
                                                                    'PENDIENTE' ||
                                                                !item.estado_seguimiento,
                                                            'bg-primary':
                                                                item.estado_seguimiento ==
                                                                'EN PROCESO',
                                                            'bg-info':
                                                                item.estado_seguimiento ==
                                                                'EN ALMACÉN',
                                                            'bg-success':
                                                                item.estado_seguimiento ==
                                                                'ENTREGADO',
                                                        }"
                                                        >{{
                                                            item.estado_seguimiento
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
                                                            verInfoSolicitud(
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
                                            paginacionSolicitudProductos.totalData
                                        "
                                        :currentPage="
                                            paginacionSolicitudProductos.currentPage
                                        "
                                        :perPage="
                                            paginacionSolicitudProductos.perPage
                                        "
                                        @updatePage="
                                            updatePageSolicitudProducto
                                        "
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
