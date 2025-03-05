<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { onMounted, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { useAxios } from "@/composables/axios/useAxios";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import MiPaginacion from "@/Components/MiPaginacion.vue";
import { useFormater } from "@/composables/useFormater";
const { getFormatoMoneda } = useFormater();

const { oConfiguracion } = useConfiguracion();
const { props: props_page } = usePage();
const user = ref(props_page.auth?.user);
const url_asset = ref(props_page.url_assets);
const listCategorias = ref([]);
const listProductos = ref([]);
const registrosFila1 = ref([]);
const registrosFila2 = ref([]);
const registrosFila3 = ref([]);
const muestraDescResultados = ref(false);
const { axiosGet } = useAxios();

const paramsProductos = ref({
    page: 1,
    perPage: 9,
    search: "",
    precioDesde: "",
    precioHasta: "",
    categoria_id: "",
    orderByCol: "",
    desc: "",
});

const dataPaginacion = ref({
    totalData: 0,
    perPage: paramsProductos.value.perPage,
    currentPage: paramsProductos.value.page,
    lastPage: 0,
});

const obtenerProductos = async () => {
    muestraDescResultados.value = false;
    const data = await axiosGet(
        route("productos.dataProductos", paramsProductos.value)
    );
    registrosFila1.value = data.productos.splice(0, 3);
    registrosFila2.value = data.productos.splice(0, 3);
    registrosFila3.value = data.productos.splice(0, 3);
    dataPaginacion.value.totalData = data.total;
    dataPaginacion.value.currentPage = paramsProductos.value.page;
    dataPaginacion.value.lastPage = data.lastPage;
    if (paramsProductos.value.search.trim() != "") {
        muestraDescResultados.value = true;
    }
};

const updatePage = (value) => {
    paramsProductos.value.page = value;
    if (paramsProductos.value.page < 0) paramsProductos.value.page = 1;
    if (paramsProductos.value.page > dataPaginacion.value.totalData)
        paramsProductos.value.page = dataPaginacion.value.lastPage;
    obtenerProductos();
};

const filtrarCategoria = (categoria_id) => {
    if (paramsProductos.value.categoria_id != categoria_id) {
        paramsProductos.value.categoria_id = categoria_id;
    } else {
        paramsProductos.value.categoria_id = "";
    }
    obtenerProductos();
};

const filtrarOrderBy = (orderCol) => {
    if (
        paramsProductos.value.desc != "asc" ||
        paramsProductos.value.orderByCol != orderCol
    ) {
        paramsProductos.value.desc = "desc";
        if (paramsProductos.value.orderByCol == orderCol) {
            paramsProductos.value.desc = "asc";
        }
        paramsProductos.value.orderByCol = orderCol;
    } else {
        paramsProductos.value.orderByCol = "";
        paramsProductos.value.desc = "";
    }
    obtenerProductos();
};

const obtenerCategorias = async () => {
    const data = await axiosGet(route("categorias.listadoPortal"));
    listCategorias.value = data.categorias;
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
    <!-- BEGIN #trending-items -->
    <div id="trending-items" class="section-container section_page">
        <!-- BEGIN container -->
        <div class="container">
            <h4 class="section-title clearfix">
                <span class="flex-1"> Productos </span>
            </h4>

            <div class="search-container">
                <!-- BEGIN search-sidebar -->
                <div class="search-sidebar">
                    <h4 class="title">Buscar por:</h4>
                    <form @submit.prevent="obtenerProductos">
                        <div class="mb-3">
                            <label class="form-label">Nombre/Descripción</label>
                            <input
                                type="text"
                                class="form-control input-sm"
                                name="keyword"
                                v-model="paramsProductos.search"
                                placeholder="Nombre/descripción"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <div class="row row-space-0">
                                <div class="col-md-5">
                                    <input
                                        type="number"
                                        class="form-control input-sm"
                                        name="price_from"
                                        min="0"
                                        placeholder="Desde"
                                        v-model="paramsProductos.precioDesde"
                                    />
                                </div>
                                <div
                                    class="col-md-2 text-center pt-5px fs-12px text-muted"
                                >
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input
                                        type="number"
                                        class="form-control input-sm"
                                        name="price_to"
                                        min="0"
                                        placeholder="Hasta"
                                        v-model="paramsProductos.precioHasta"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mb-30px">
                            <button
                                type="submit"
                                class="btn btn-sm btn-theme btn-dark w-100"
                            >
                                <i class="fa fa-search fa-fw me-1 ms-n3"></i>
                                BUSCAR
                            </button>
                        </div>
                    </form>
                    <h4 class="title mb-0">Categorías</h4>
                    <ul class="search-category-list">
                        <li>
                            <a
                                href="#"
                                class="categoria px-1"
                                :class="
                                    item.id == paramsProductos.categoria_id
                                        ? 'active'
                                        : ''
                                "
                                v-for="item in listCategorias"
                                @click.prevent="filtrarCategoria(item.id)"
                                >{{ item.nombre }}</a
                            >
                        </li>
                    </ul>
                </div>
                <!-- END search-sidebar -->
                <!-- BEGIN search-content -->
                <div class="search-content">
                    <!-- BEGIN search-toolbar -->
                    <div class="search-toolbar">
                        <!-- BEGIN row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 v-show="muestraDescResultados != ''">
                                    Encontramos
                                    {{ dataPaginacion.totalData }} registro(s)
                                    para "{{ paramsProductos.search }}"
                                </h4>
                            </div>
                            <!-- END col-6 -->
                            <!-- BEGIN col-6 -->
                            <div class="col-lg-6 text-end">
                                <ul class="sort-list">
                                    <li class="text">
                                        <i
                                            class="fa"
                                            :class="
                                                paramsProductos.desc == 'desc'
                                                    ? 'fa-sort-amount-up'
                                                    : paramsProductos.desc ==
                                                      'asc'
                                                    ? 'fa-sort-amount-down-alt'
                                                    : 'fa-filter'
                                            "
                                        ></i>
                                        Ordenar por:
                                    </li>
                                    <li
                                        :class="
                                            paramsProductos.orderByCol ==
                                            'total_vendido'
                                                ? 'active'
                                                : ''
                                        "
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="
                                                filtrarOrderBy('total_vendido')
                                            "
                                            >Popular</a
                                        >
                                    </li>
                                    <li
                                        :class="
                                            paramsProductos.orderByCol == 'id'
                                                ? 'active'
                                                : ''
                                        "
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="
                                                filtrarOrderBy('id')
                                            "
                                            >Nuevos</a
                                        >
                                    </li>
                                    <li
                                        :class="
                                            paramsProductos.orderByCol ==
                                            'precio_venta'
                                                ? 'active'
                                                : ''
                                        "
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="
                                                filtrarOrderBy('precio_venta')
                                            "
                                            >Precio</a
                                        >
                                    </li>
                                </ul>
                            </div>
                            <!-- END col-6 -->
                        </div>
                        <!-- END row -->
                    </div>
                    <!-- END search-toolbar -->
                    <!-- BEGIN search-item-container -->
                    <div class="search-item-container">
                        <!-- BEGIN item-row -->
                        <div class="item-row" v-if="registrosFila1.length > 0">
                            <!-- BEGIN item -->
                            <div
                                class="item item-thumbnail"
                                v-for="item in registrosFila1"
                            >
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                </Link>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <a
                                            :href="
                                                route(
                                                    'portal.producto',
                                                    item.id
                                                )
                                            "
                                            >{{ item.nombre }}</a
                                        >
                                    </h4>
                                    <p class="item-desc">
                                        {{ item.descripcion }}
                                    </p>
                                    <div class="item-price">
                                        {{ oConfiguracion.conf_moneda.abrev }}
                                        {{ getFormatoMoneda(item.precio_venta) }}
                                    </div>
                                </div>
                            </div>
                            <!-- END item -->
                        </div>
                        <!-- END item-row -->
                        <!-- BEGIN item-row -->
                        <div class="item-row" v-if="registrosFila2.length > 0">
                            <!-- BEGIN item -->
                            <div
                                class="item item-thumbnail"
                                v-for="item in registrosFila2"
                            >
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                </Link>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <a
                                            :href="
                                                route(
                                                    'portal.producto',
                                                    item.id
                                                )
                                            "
                                            >{{ item.nombre }}</a
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
                        <div class="item-row" v-if="registrosFila3.length > 0">
                            <!-- BEGIN item -->
                            <div
                                class="item item-thumbnail"
                                v-for="item in registrosFila3"
                            >
                                <Link
                                    :href="route('portal.producto', item.id)"
                                    class="item-image"
                                >
                                    <img
                                        :src="item.imagens[0].url_imagen"
                                        alt=""
                                    />
                                </Link>
                                <div class="item-info">
                                    <h4 class="item-title">
                                        <a
                                            :href="
                                                route(
                                                    'portal.producto',
                                                    item.id
                                                )
                                            "
                                            >{{ item.nombre }}</a
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
                    <!-- END search-item-container -->
                    <!-- BEGIN pagination -->
                    <MiPaginacion
                        class="justify-content-center"
                        :totalData="dataPaginacion.totalData"
                        :currentPage="dataPaginacion.currentPage"
                        :perPage="dataPaginacion.perPage"
                        @updatePage="updatePage"
                    />
                    <!-- END pagination -->
                </div>
                <!-- END search-content -->
            </div>
        </div>
        <!-- END container -->
    </div>
    <!-- END #trending-items -->
</template>
<style scoped>
.section-container {
    min-height: 62vh;
}

.categoria.active {
    background-color: var(--principal-portal2);
    color: white !important;
    font-weight: bold;
}

.sort-list li.active {
    font-weight: bold;
}
</style>
