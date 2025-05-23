<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { onMounted, ref, inject, computed, nextTick } from "vue";
import { useOrdenVentaStore } from "@/stores/ordenVenta/ordenVentaStore";
import LoginModal from "@/Components/LoginModal.vue";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { useAxios } from "@/composables/axios/useAxios";
import Vue3Recaptcha2 from "vue3-recaptcha2";
import { useFormater } from "@/composables/useFormater";
const { getFormatoMoneda } = useFormater();
const ordenVentaStore = useOrdenVentaStore();
const { oConfiguracion } = useConfiguracion();
const { axiosGet, axiosPostFormData } = useAxios();
const carrito = ref(ordenVentaStore.getCarrito());
const { auth } = usePage().props;
const pasoActual = ref(1);
const actualizarCantidad = (e, index) => {
    const value = e.target.value;
    carrito.value = ordenVentaStore.editQuantity(index, value);
};

const eliminarFila = (index) => {
    carrito.value = ordenVentaStore.deleteProducto(index);
};

const listConfiguracionPagos = ref([]);
const indexConfiguracionPago = ref(-1);
const swIrPasos = ref(true); //CAMBIAR A FALSE

const actualizarPaso = (value) => {
    if (verificaInicioSesion()) {
        pasoActual.value = pasoActual.value + value;
        if (pasoActual.value < 1) {
            pasoActual.value = 1;
        }

        if (pasoActual.value > 4) {
            pasoActual.value = 4;
        }

        if (pasoActual.value == 4) {
            swIrPasos.value = true;
        }
    }
};

const irPaso = (paso) => {
    if (verificaInicioSesion()) {
        pasoActual.value = paso;
    }
};

const cambiarConfigPago = (value) => {
    indexConfiguracionPago.value += parseInt(value);
    if (indexConfiguracionPago < 0) {
        indexConfiguracionPago.value = 0;
    }

    if (indexConfiguracionPago > listConfiguracionPagos.length - 1) {
        indexConfiguracionPago.value = listConfiguracionPagos.length - 1;
    }
};

const irConfigPago = (index) => {
    indexConfiguracionPago.value = index;
};

const cargarConfiguracionPagos = async () => {
    indexConfiguracionPago.value = -1;
    const data = await axiosGet(route("portal.configuracionPagosLista"));
    listConfiguracionPagos.value = data.configuracionPagos;
    if (listConfiguracionPagos.value.length > 0) {
        indexConfiguracionPago.value = 0;
    }
};

const modalLogin = ref(false);

const verificaInicioSesion = () => {
    if (!auth.user) {
        modalLogin.value = true;
        return null;
    }
    return auth;
};

const inputComprobante = ref(null);
const comprobante = ref(null);
const cargarComprobante = (e) => {
    const target = e.target;
    comprobante.value = null;
    if (target) {
        if (target.files[0]) {
            comprobante.value = target.files[0];
        }
    }
};

const recaptchaRef = ref(null);
const token = ref("");

const onVerify = (response) => {
    token.value = response;
};

const resetRecaptcha = () => {
    recaptchaRef.value.reset(); // Reiniciar el captcha
};

const enviando = ref(false);
const errors = ref(null);
const registrarOrdenVenta = async () => {
    errors.value = null;
    enviando.value = true;
    if (verificaInicioSesion()) {
        let formdata = new FormData();
        formdata.append("cliente_id", auth.user.cliente.id);
        formdata.append("token_captcha", token.value);
        carrito.value.forEach((element) => {
            formdata.append(
                "carrito[]",
                JSON.stringify({
                    cantidad: element.cantidad,
                    producto_id: element.producto_id,
                    precio: element.precio,
                    subtotal: element.subtotal,
                })
            );
        });

        formdata.append("comprobante", comprobante.value);
        formdata.append(
            "configuracion_pago_id",
            listConfiguracionPagos.value[indexConfiguracionPago.value].id
        );
        try {
            const resp = await axiosPostFormData(
                route("orden_ventas.store"),
                formdata
            );
            reiniciarCarrito();
        } catch (error) {
            console.log(error);
            if (error.response && error.response.data.errors) {
                errors.value = error.response.data.errors;
            }
        } finally {
            enviando.value = false;
        }
    }
};

const reiniciarCarrito = () => {
    inputComprobante.value.value = null;
    ordenVentaStore.limpiarCarrito();
    carrito.value = [];
    pasoActual.value = 1;
    resetRecaptcha();
    indexConfiguracionPago.value = -1;
    if (listConfiguracionPagos.value.length > 0) {
        indexConfiguracionPago.value = 0;
    }
};

onMounted(async () => {
    oConfiguracion.value = await useConfiguracion().getConfiguracion();
    cargarConfiguracionPagos();
    ordenVentaStore.initCarrito();
    carrito.value = ordenVentaStore.getCarrito();
});
</script>
<template>
    <LoginModal
        :openModal="modalLogin"
        @cerrar-modal="modalLogin = false"
    ></LoginModal>
    <div id="carrito" class="section-container pt-20px">
        <div class="container">
            <h4 class="section-title clearfix">
                <span class="flex-1">
                    Mi carrito
                </span>
            </h4>
            <div class="contenedor_pasos overflow-auto">
                <button
                    class="item_paso"
                    :class="pasoActual == 1 ? 'active' : ''"
                    @click="irPaso(1)"
                >
                    <span>1</span>Carrito
                </button>
                <button
                    class="item_paso"
                    :class="[
                        pasoActual == 2 ? 'active' : '',
                        !swIrPasos && pasoActual < 2 ? 'disabled' : '',
                    ]"
                    @click="irPaso(2)"
                    :disabled="!swIrPasos && pasoActual < 2"
                >
                    <span>2</span>Datos de compra
                </button>
                <button
                    class="item_paso"
                    :class="[
                        pasoActual == 3 ? 'active' : '',
                        !swIrPasos && pasoActual < 3 ? 'disabled' : '',
                    ]"
                    @click="irPaso(3)"
                    :disabled="!swIrPasos && pasoActual < 3"
                >
                    <span>3</span>Confirmación
                </button>
                <button
                    class="item_paso"
                    :class="[
                        pasoActual == 4 ? 'active' : '',
                        !swIrPasos && pasoActual < 4 ? 'disabled' : '',
                    ]"
                    @click="irPaso(4)"
                    :disabled="!swIrPasos && pasoActual < 4"
                >
                    <span>4</span>Pago
                </button>
            </div>
            <div class="row mb-2 mt-1">
                <!-- paso 1 -->
                <div class="col-12 overflow-auto" v-show="pasoActual == 1">
                    <table class="table table-striped bg-white">
                        <thead>
                            <tr class="bg-dark">
                                <th class="text-white">#</th>
                                <th class="text-white" colspan="2">PRODUCTO</th>
                                <th class="text-white text-center">CANTIDAD</th>
                                <th class="text-white text-right">PRECIO</th>
                                <th class="text-white text-right">SUBTOTAL</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="carrito.length > 0">
                                <tr v-for="(item, index) in carrito">
                                    <td>{{ index + 1 }}</td>
                                    <td width="120">
                                        <img
                                            :src="item.url_imagen"
                                            width="120"
                                            alt=""
                                        />
                                    </td>
                                    <td>{{ item.nombre_prod }}</td>
                                    <td class="text-right">
                                        <input
                                            type="number"
                                            class="form-control text-center"
                                            min="1"
                                            v-model="item.cantidad"
                                            @keyup="
                                                actualizarCantidad(
                                                    $event,
                                                    index
                                                )
                                            "
                                            @change="
                                                actualizarCantidad(
                                                    $event,
                                                    index
                                                )
                                            "
                                        />
                                    </td>
                                    <td class="text-right">
                                        {{ getFormatoMoneda(item.precio) }}
                                    </td>
                                    <td class="text-right">
                                        {{ getFormatoMoneda(item.subtotal) }}
                                    </td>
                                    <td class="text-right">
                                        <button
                                            type="button"
                                            class="rounded"
                                            @click="eliminarFila(index)"
                                        >
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="7" class="text-center text-muted">
                                    NO SE AGREGARÓN PRODUCTOS
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-dark">
                                <td
                                    class="text-right text-white h5"
                                    colspan="5"
                                >
                                    TOTAL
                                </td>
                                <td class="text-right text-white h5">
                                    {{ oConfiguracion.conf_moneda?.abrev }}
                                    {{ getFormatoMoneda(ordenVentaStore.getSumaTotalCarrito()) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- paso 2 -->
                <div class="col-12 overflow-auto" v-show="pasoActual == 2">
                    <form class="bg-white p-3">
                        <div class="row">
                            <div class="col-12">
                                <div
                                    class="alert alert-info text-sm text-muted"
                                >
                                    Los campos con * son obligatorios
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nombre(s)*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="auth?.user?.nombres"
                                    readonly
                                />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Apellidos*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="auth?.user?.apellidos"
                                    readonly
                                />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Celular*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="auth?.user?.cliente?.cel"
                                    readonly
                                />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correo electrónico*</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    :value="auth?.user?.correo"
                                    readonly
                                />
                            </div>
                        </div>
                    </form>
                </div>
                <!-- paso 3 -->
                <div class="col-12 overflow-auto" v-show="pasoActual == 3">
                    <p class="alert alert-info mb-0">
                        Confirmación de productos agregados
                    </p>
                    <table class="table table-striped bg-white">
                        <thead>
                            <tr class="bg-dark">
                                <th class="text-white">#</th>
                                <th class="text-white" colspan="2">PRODUCTO</th>
                                <th class="text-white text-center">CANTIDAD</th>
                                <th class="text-white text-right">PRECIO</th>
                                <th class="text-white text-right">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in carrito">
                                <td>{{ index + 1 }}</td>
                                <td width="120">
                                    <img
                                        :src="item.url_imagen"
                                        width="120"
                                        alt=""
                                    />
                                </td>
                                <td>{{ item.nombre_prod }}</td>
                                <td class="text-center">
                                    {{ item.cantidad }}
                                </td>
                                <td class="text-right">{{ getFormatoMoneda(item.precio) }}</td>
                                <td class="text-right">{{ getFormatoMoneda(item.subtotal) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-dark">
                                <td
                                    class="text-right text-white h5"
                                    colspan="5"
                                >
                                    TOTAL
                                </td>
                                <td class="text-right text-white h5">
                                    {{ oConfiguracion.conf_moneda?.abrev }}
                                    {{ getFormatoMoneda(ordenVentaStore.getSumaTotalCarrito()) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- paso 4 -->
                <div class="col-12 overflow-auto" v-show="pasoActual == 4">
                    <form class="bg-white">
                        <div class="row">
                            <div
                                class="col-12"
                                v-if="indexConfiguracionPago == -1"
                            >
                                <div class="alert alert-danger">
                                    Lo sentimos no se pudieron cargar los
                                    metodos de pago, por favor intente mas
                                    tarde.<br />Si el problema persiste
                                    comuniquese con nosotros
                                </div>
                            </div>
                            <div
                                class="col-12"
                                v-if="indexConfiguracionPago > -1"
                            >
                                <p class="alert alert-info mb-0">
                                    Seleccionar medio de pago
                                </p>
                                <div class="contenedor_cards">
                                    <div class="cards_pagos">
                                        <button
                                            type="button"
                                            class="izquierda"
                                            v-show="indexConfiguracionPago > 0"
                                            @click="cambiarConfigPago(-1)"
                                        >
                                            <i class="fa fa-arrow-left"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="derecha"
                                            v-show="
                                                indexConfiguracionPago <
                                                listConfiguracionPagos.length -
                                                    1
                                            "
                                            @click="cambiarConfigPago(1)"
                                        >
                                            <i class="fa fa-arrow-right"></i>
                                        </button>
                                        <div
                                            class="card_pago"
                                            v-for="(
                                                item, index
                                            ) in listConfiguracionPagos"
                                            v-show="
                                                indexConfiguracionPago == index
                                            "
                                        >
                                            <p class="mb-1 font-weight-bold">
                                                {{ item.nombre_banco }}
                                            </p>
                                            <p class="mb-1">
                                                {{ item.nro_cuenta }}
                                            </p>
                                            <p class="mb-1">
                                                {{ item.titular_cuenta }}
                                            </p>
                                            <img
                                                :src="item.url_imagen_qr"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                    <div class="puntosNavConfPagos">
                                        <button
                                            type="button"
                                            v-for="(
                                                item, index
                                            ) in listConfiguracionPagos"
                                            @click="irConfigPago(index)"
                                        >
                                            <i
                                                class="fa-circle"
                                                :class="[
                                                    indexConfiguracionPago ==
                                                    index
                                                        ? 'fa'
                                                        : 'far',
                                                ]"
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <p
                                    class="w-100 text-center h3 bg-dark text-white py-3"
                                >
                                    TOTAL A PAGAR:
                                    {{ oConfiguracion.conf_moneda?.abrev }}
                                    {{ getFormatoMoneda(ordenVentaStore.getSumaTotalCarrito()) }}
                                </p>
                            </div>
                            <div class="col-12 form-group text-center mb-3">
                                <label>Cargar comprobante de pago*</label><br />
                                <input
                                    type="file"
                                    ref="inputComprobante"
                                    @change="cargarComprobante($event)"
                                />
                                <span
                                    class="text-danger font-weight-bold d-block"
                                    v-if="errors && errors.comprobante"
                                    >{{ errors.comprobante[0] }}</span
                                >
                            </div>
                            <hr />
                            <div class="col-12 form-group text-center mb-3">
                                <Vue3Recaptcha2
                                    class="d-flex justify-content-center w-100"
                                    v-if="
                                        oConfiguracion &&
                                        oConfiguracion.conf_captcha &&
                                        oConfiguracion.conf_captcha.claveSitio
                                    "
                                    ref="recaptchaRef"
                                    :sitekey="
                                        oConfiguracion?.conf_captcha?.claveSitio
                                    "
                                    @verify="onVerify"
                                />
                                <span
                                    class="text-danger font-weight-bold d-block"
                                    v-if="errors && errors.token_captcha"
                                    >{{ errors.token_captcha[0] }}</span
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" v-if="carrito.length > 0">
                <div class="col-md-6">
                    <button
                        v-show="pasoActual > 1"
                        class="btn btn-white btn-lg me-auto btn-theme w-100"
                        @click="actualizarPaso(-1)"
                    >
                        <i class="fa fa-arrow-left"></i> Volver
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <button
                        v-if="pasoActual < 4"
                        type="button"
                        class="btn btn-dark btn-lg btn-theme w-100"
                        @click="actualizarPaso(1)"
                    >
                        Continuar <i class="fa fa-arrow-right"></i>
                    </button>
                    <button
                        v-if="pasoActual == 4 && indexConfiguracionPago != -1"
                        type="button"
                        class="btn btn-dark btn-lg btn-theme w-100"
                        @click="registrarOrdenVenta"
                        :disabled="token == '' || enviando"
                    >
                        <span
                            v-text="enviando ? 'Registrando ' : 'Finalizar '"
                        ></span>
                        <i
                            class="fa"
                            :class="[
                                enviando
                                    ? 'fa-spinner fa-spin'
                                    : 'fa-check-circle',
                            ]"
                        ></i>
                    </button>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-6">
                    <Link
                        :href="route('portal.productos')"
                        class="btn btn-white btn-lg me-auto btn-theme w-100"
                        ><i class="fa fa-table"></i> Ver productos</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.section-container {
    min-height: 70vh !important;
}

.contenedor_pasos {
    display: flex;
    margin-bottom: 1px;
    padding: 10px 0px;
    background-color: var(--principal-portal);
}

.item_paso {
    display: flex;
    padding: 10px;
    flex: 1;
    color: white;
    border: none;
    justify-content: center;
    align-items: center;
    background-color: transparent;
}

.item_paso span {
    margin-right: 5px;
    padding: 10px 15px;
    border-radius: 50%;
    background-color: rgb(218, 218, 218);
    color: black;
}

.item_paso.disabled span {
    background-color: rgb(122, 122, 122);
    color: rgb(90, 90, 90);
}

.item_paso.active span {
    color: white;
    background-color: var(--bg3);
}

.contenedor_cards {
    width: 100%;
    overflow: hidden;
    padding-bottom: 10px;
}

.cards_pagos {
    margin: auto;
    position: relative;
    width: 60%;
}

.cards_pagos .izquierda,
.cards_pagos .derecha {
    border: solid 1px gray;
    position: absolute;
    top: 50%;
    z-index: 10000000;
}

.puntosNavConfPagos {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 4px;
    width: 100%;
}
.puntosNavConfPagos button {
    border: none;
    padding: 0px;
    background-color: transparent;
}

.contenedor_cards .izquierda {
    left: -10px;
}
.contenedor_cards .derecha {
    right: -10px;
}

.cards_pagos .card_pago {
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border: solid 1px gray;
    background-color: rgb(238, 238, 238);
    margin: auto;
    box-shadow: 0px 5px 8px -2px black;
}
.cards_pagos .card_pago img {
    max-width: 200px;
}
</style>
