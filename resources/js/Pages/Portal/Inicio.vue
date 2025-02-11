<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { onMounted, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import PublicacionLista from "@/Components/PublicacionLista.vue";
import { useAxios } from "@/composables/axios/useAxios";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";

const { oConfiguracion } = useConfiguracion();
const { props: props_page } = usePage();
const user = ref(props_page.auth?.user);
const url_asset = ref(props_page.url_assets);
const { axiosGet } = useAxios();
const listCategorias = ref([]);
const listProductos1 = ref([]);
const listProductos2 = ref([]);
const productoPrincipal = ref([]);
const paramsProductos = ref({
    tomar: 7,
    categoria_id: "",
});

const obtenerProductos = async () => {
    const data = await axiosGet(
        route("productos.productosInicioPortal"),
        paramsProductos.value
    );
    productoPrincipal.value = data[0];
    listProductos1.value = data.splice(1, 3);
    listProductos2.value = data.splice(1, 7);
};

const obtenerCategorias = async () => {
    const data = await axiosGet(route("categorias.listadoPortal"));
    listCategorias.value = data.categorias;
};

const filtraRegistros = (categoria_id) => {
    if (paramsProductos.value.categoria_id != categoria_id) {
        paramsProductos.value.categoria_id = categoria_id;
    } else {
        paramsProductos.value.categoria_id = "";
    }
    obtenerProductos();
};

const cargarListas = () => {
    obtenerProductos();
    obtenerCategorias();
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <!-- BEGIN #productos -->
    <div id="productos" class="productos pt-5 pb-5 mb-5">
        <!-- BEGIN container -->
        <div class="container mb-0">
            <h4 class="section-title clearfix">
                <span class="flex-1">
                    Nuestros productos
                    <small
                        >¡Compra y consigue tu producto favorito a precios
                        increíbles!</small
                    >
                </span>
                <a href="#" class="btn">Ver todos</a>
            </h4>
            <div class="category-container">
                <!-- BEGIN category-sidebar -->
                <div class="category-sidebar">
                    <ul class="category-list">
                        <li class="list-header">Categorías</li>
                        <li v-for="item in listCategorias">
                            <a
                                href="#"
                                class="categoria px-1"
                                :class="
                                    item.id == paramsProductos.categoria_id
                                        ? 'active'
                                        : ''
                                "
                                @click.prevent="filtraRegistros(item.id)"
                                >{{ item.nombre }}</a
                            >
                        </li>
                    </ul>
                </div>
                <!-- END category-sidebar -->
                <!-- BEGIN category-detail -->
                <div class="category-detail">
                    <!-- BEGIN category-item -->
                    <a href="#" class="category-item full">
                        <div
                            class="item"
                            v-if="
                                productoPrincipal && productoPrincipal.imagens
                            "
                        >
                            <div class="item-cover">
                                <img
                                    :src="
                                        productoPrincipal.imagens[0].url_imagen
                                    "
                                    alt=""
                                />
                            </div>
                            <div class="item-info bottom">
                                <h4 class="item-title">
                                    {{ productoPrincipal.nombre }}
                                </h4>
                                <p class="item-desc">
                                    {{ productoPrincipal.descripcion }}
                                </p>
                                <div class="item-price">
                                    {{ oConfiguracion.conf_moneda.abrev }}
                                    {{ productoPrincipal.precio_venta }}
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END category-item -->
                    <!-- BEGIN category-item -->
                    <div class="category-item list">
                        <!-- BEGIN item-row -->
                        <div class="item-row" v-if="listProductos1.length > 0">
                            <!-- BEGIN item -->
                            <div
                                class="item item-thumbnail"
                                v-for="item in listProductos1"
                            >
                                <a
                                    href="product_detail.html"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                    <!-- <div class="discount">15% OFF</div> -->
                                </a>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <a href="product_detail.html">{{
                                            item.nombre
                                        }}</a>
                                    </h4>
                                    <p class="item-desc">
                                        {{ item.descripcion }}
                                    </p>
                                    <div class="item-price">
                                        {{ oConfiguracion.conf_moneda.abrev }}
                                        {{ item.precio_venta }}
                                    </div>
                                </div>
                            </div>
                            <!-- END item -->
                        </div>
                        <!-- END item-row -->
                        <!-- BEGIN item-row -->
                        <div class="item-row" v-if="listProductos2.length > 0">
                            <!-- BEGIN item -->
                            <div
                                class="item item-thumbnail"
                                v-for="item in listProductos2"
                            >
                                <a
                                    href="product_detail.html"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                    <!-- <div class="discount">15% OFF</div> -->
                                </a>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <a href="product_detail.html">{{
                                            item.nombre
                                        }}</a>
                                    </h4>
                                    <p class="item-desc">
                                        {{ item.descripcion }}
                                    </p>
                                    <div class="item-price">
                                        {{ oConfiguracion.conf_moneda.abrev }}
                                        {{ item.precio_venta }}
                                    </div>
                                </div>
                            </div>
                            <!-- END item -->
                        </div>
                        <!-- END item-row -->
                    </div>
                    <!-- END category-item -->
                </div>
                <!-- END category-detail -->
            </div>
        </div>
        <!-- END container -->
    </div>
    <!-- END #productos -->
</template>

<style scoped>
#productos {
    min-height: 62vh;
}
.categoria.active {
    background-color: var(--principal-portal2);
    color: white !important;
    font-weight: bold;
}
</style>
