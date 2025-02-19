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
const { oConfiguracion } = useConfiguracion();
const { props: props_page } = usePage();
const user = ref(props_page.auth?.user);
const url_asset = ref(props_page.url_assets);
const { axiosGet } = useAxios();
const listCategorias = ref([]);
const listProductos1 = ref([]);
const listProductos2 = ref([]);
const productoPrincipal = ref([]);
const listPopulares = ref([]);
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

const obtenerProductosPopulares = async () => {
    const data = await axiosGet(
        route("productos.populares"),
        paramsProductos.value
    );
    listPopulares.value = data;
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

const mostrarProdPrincipal = () => {
    if (productoPrincipal.value) {
        router.get(route("portal.producto", productoPrincipal.value.id));
    }
};

const cargarListas = () => {
    obtenerProductos();
    obtenerCategorias();
    obtenerProductosPopulares();
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <div id="trending-items" class="section-container">
        <!-- BEGIN container -->
        <div class="container">
            <!-- BEGIN section-title -->
            <h4 class="section-title clearfix">
                <span class="flex-1">
                    Productos populares
                    <small></small>
                </span>
                <Link :href="route('portal.productos')" class="btn"
                    >Ver todos</Link
                >
                <!-- <div class="btn-group">
                    <a href="#" class="btn"
                        ><i class="fa fa-angle-left fs-16px"></i
                    ></a>
                    <a href="#" class="btn"
                        ><i class="fa fa-angle-right fs-16px"></i
                    ></a>
                </div> -->
            </h4>
            <!-- END section-title -->
            <!-- BEGIN row -->
            <div class="row gx-2">
                <!-- BEGIN col-2 -->
                <div
                    class="col-lg-2 col-md-4 col-sm-6"
                    v-for="item in listPopulares"
                >
                    <!-- BEGIN item -->
                    <div class="item item-thumbnail">
                        <a href="product_detail.html" class="item-image">
                            <img :src="item.imagens[0].url_imagen" alt="" />
                        </a>
                        <div class="item-info">
                            <h4 class="item-title">
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    >{{ item.nombre }}</Link
                                >
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
                <!-- END col-2 -->
            </div>
            <!-- END row -->
        </div>
        <!-- END container -->
    </div>

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
                <Link :href="route('portal.productos')" class="btn"
                    >Ver todos</Link
                >
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
                    <a
                        v-if="productoPrincipal"
                        href="#"
                        @click.prevent="mostrarProdPrincipal"
                        class="category-item full"
                    >
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
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                    <!-- <div class="discount">15% OFF</div> -->
                                </Link>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <Link
                                            :href="
                                                route(
                                                    'portal.producto',
                                                    item.id
                                                )
                                            "
                                            >{{ item.nombre }}</Link
                                        >
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
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                    <!-- <div class="discount">15% OFF</div> -->
                                </Link>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <Link
                                            :href="
                                                route(
                                                    'portal.producto',
                                                    item.id
                                                )
                                            "
                                            >{{ item.nombre }}</Link
                                        >
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
