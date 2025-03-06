<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { onMounted, ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { useAxios } from "@/composables/axios/useAxios";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import MiPaginacion from "@/Components/MiPaginacion.vue";
import infoOrdenVenta from "@/Components/infoOrdenVenta.vue";
import InfoSolicitudProducto from "@/Components/InfoSolicitudProducto.vue";
import Vue3Recaptcha2 from "vue3-recaptcha2";
import LoginModal from "@/Components/LoginModal.vue";
const { oConfiguracion } = useConfiguracion();
const { props: props_page } = usePage();
const { auth } = props_page;
const { axiosGet } = useAxios();
const nroTab = ref(1);

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
    obtenerSolicitudProductos();
};

const irTab = (value) => {
    nroTab.value = value;
};

// LOGIN
const modalLogin = ref(false);

const verificaInicioSesion = () => {
    if (!auth.user) {
        modalLogin.value = true;
        return null;
    }
    return auth;
};

// FORMULARIO
const enviando = ref(false);
const form = ref({
    sede_id: "",
    cliente_id: auth?.user?.cliente.id,
    token_captcha: "",
    solicitudes: [
        {
            nombre_producto: "",
            detalle_producto: "",
            links_referencia: "",
        },
    ],
});
const errors = ref(null);
const recaptchaRef = ref(null);
const token = ref("");

const onVerify = (response) => {
    token.value = response;
};

const resetRecaptcha = () => {
    recaptchaRef.value.reset(); // Reiniciar el captcha
};

const txtBotonEnviar = computed(() => {
    if (enviando.value) {
        return `<i class="fa fa-spinner fa-spin"></i> Enviando...`;
    }
    return `<i class="fa fa-send"></i> Registrar solicitud`;
});

const enviarFormulario = () => {
    if (verificaInicioSesion()) {
        form.value.token_captcha = token.value;
        enviando.value = true;
        axios
            .post(route("solicitud_productos.store"), form.value)
            .then((response) => {
                errors.value = null;
                resetRecaptcha();
                Swal.fire({
                    icon: "success",
                    title: "Correcto",
                    text: `${
                        response.message
                            ? response.message
                            : "Proceso realizado"
                    }`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                cargarListas();
                setTimeout(() => {
                    nroTab.value = 2;
                }, 400);
            })
            .catch((error) => {
                console.log("ERROR");
                console.log(error);
                if (error.response && error.response.status == 422) {
                    errors.value = error.response.data.errors;
                    Swal.fire({
                        icon: "info",
                        title: "Error",
                        text: `Tienes errores en el formulario`,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: `Aceptar`,
                    });
                } else if (error.response && error.response.status == 400) {
                    Swal.fire({
                        icon: "info",
                        title: "Error",
                        text: `${error.response.data.message}`,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: `Aceptar`,
                    });
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "Error",
                        text: `Ocurrió un error inesperado intente mas tarde por favor`,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: `Aceptar`,
                    });
                }
            })
            .finally(() => {
                enviando.value = false;
            });
    }
};

const listSedes = ref([]);
const cargarSedes = async () => {
    const data = await axiosGet(route("sedes.listado"));
    listSedes.value = data.sedes;
};

onMounted(async () => {
    await cargarSedes();
    if (auth && auth.user && auth.user.role_id == 2) {
        cargarListas();
    }
});
</script>
<template>
    <LoginModal
        :openModal="modalLogin"
        @cerrar-modal="modalLogin = false"
    ></LoginModal>
    <InfoSolicitudProducto
        :solicitudProducto="oSolicitudProducto"
        :open_dialog="modalInfoSolicitudProducto"
        @cerrar-dialog="modalInfoSolicitudProducto = false"
    ></InfoSolicitudProducto>

    <!-- BEGIN #productos -->
    <div id="misSolicitudes" class="productos pt-5 pb-5 mb-5">
        <!-- BEGIN container -->
        <div class="container mb-0">
            <h4 class="section-title clearfix">
                <span class="flex-1"> Solicitud de productos </span>
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
                                Formulario de solicitud
                            </div>
                            <div
                                v-if="
                                    auth && auth.user && auth.user.role_id == 2
                                "
                                class="item-nav"
                                :class="[nroTab == 2 ? 'active' : '']"
                                @click.stop="irTab(2)"
                            >
                                <span>Mis solicitudes</span>
                            </div>
                        </div>
                        <!-- SOLICITUD PRODUCTOS -->
                        <div class="contenedor-nav bg-white" v-if="nroTab == 1">
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <h5>Solicita tu producto del exterior</h5>
                                </div>
                                <div class="col-12">
                                    <form @submit.prevent="enviarFormulario">
                                        <div
                                            class="row"
                                            v-if="auth && auth.user"
                                        >
                                            <div class="col-12">
                                                <div
                                                    class="alert alert-info text-sm"
                                                >
                                                    Los campos con * son
                                                    obligatorios
                                                </div>
                                            </div>
                                            <h4>SOLICITANTE</h4>
                                            <div class="col-md-6 form-group">
                                                <label>Nombres*</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    readonly
                                                    :value="
                                                        auth?.user?.cliente
                                                            ?.nombres
                                                    "
                                                />
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Apellidos*</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    readonly
                                                    :value="
                                                        auth?.user?.cliente
                                                            ?.apellidos
                                                    "
                                                />
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Celular*</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    readonly
                                                    :value="
                                                        auth?.user?.cliente?.cel
                                                    "
                                                />
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label
                                                    >Correo electrónico*</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    readonly
                                                    :value="
                                                        auth?.user?.cliente
                                                            ?.correo
                                                    "
                                                />
                                            </div>
                                        </div>
                                        <hr />
                                        <div
                                            class="row"
                                            v-for="item in form.solicitudes"
                                        >
                                            <h4>PRODUCTO SOLICITADO</h4>
                                            <div class="col-md-4 form-group">
                                                <label
                                                    >Nombre del producto*</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="
                                                        item.nombre_producto
                                                    "
                                                    :readonly="
                                                        !auth || !auth.user
                                                    "
                                                />
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <label
                                                    >Detalle del
                                                    producto*</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="
                                                        item.detalle_producto
                                                    "
                                                    :readonly="
                                                        !auth || !auth.user
                                                    "
                                                />
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label
                                                    >Links de referencia del
                                                    producto*</label
                                                >
                                                <el-input
                                                    type="textarea"
                                                    v-model="
                                                        item.links_referencia
                                                    "
                                                    :readonly="
                                                        !auth || !auth.user
                                                    "
                                                    autosize
                                                ></el-input>
                                            </div>
                                        </div>
                                        <div
                                            class="row"
                                            v-if="errors && errors.solicitudes"
                                        >
                                            <div class="col-12">
                                                <div
                                                    class="alert alert-danger text-sm border-0"
                                                >
                                                    {{ errors.solicitudes[0] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label
                                                    >De que departamento
                                                    solicita el producto*</label
                                                >
                                                <select
                                                    class="form-select"
                                                    v-model="form.sede_id"
                                                >
                                                    <option value="">
                                                        - Seleccione -
                                                    </option>
                                                    <option
                                                        v-for="item in listSedes"
                                                        :value="item.id"
                                                    >
                                                        {{ item.nombre }}
                                                    </option>
                                                </select>

                                                <span
                                                    class="text-danger font-weight-bold d-block"
                                                    v-if="
                                                        errors && errors.sede_id
                                                    "
                                                    >{{
                                                        errors.sede_id[0]
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            class="row mt-2"
                                            v-if="auth && auth.user"
                                        >
                                            <div
                                                class="col-12 form-group text-center mb-3"
                                            >
                                                <Vue3Recaptcha2
                                                    class="d-flex justify-content-center w-100"
                                                    v-if="
                                                        oConfiguracion &&
                                                        oConfiguracion.conf_captcha &&
                                                        oConfiguracion
                                                            .conf_captcha
                                                            .claveSitio
                                                    "
                                                    ref="recaptchaRef"
                                                    :sitekey="
                                                        oConfiguracion
                                                            ?.conf_captcha
                                                            ?.claveSitio
                                                    "
                                                    @verify="onVerify"
                                                />
                                                <span
                                                    class="text-danger font-weight-bold d-block"
                                                    v-if="
                                                        errors &&
                                                        errors.token_captcha
                                                    "
                                                    >{{
                                                        errors.token_captcha[0]
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button
                                                    v-if="auth && auth.user"
                                                    type="button"
                                                    @click="enviarFormulario()"
                                                    class="btn btn-dark btn-lg"
                                                    v-html="txtBotonEnviar"
                                                    :disabled="enviando"
                                                ></button>
                                                <button
                                                    v-else
                                                    class="btn btn-warning btn-lg"
                                                    @click="modalLogin = true"
                                                >
                                                    Inicia sesión para continuar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
