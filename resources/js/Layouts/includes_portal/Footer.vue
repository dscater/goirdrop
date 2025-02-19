<script setup>
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { useOrdenVentaStore } from "@/stores/ordenVenta/ordenVentaStore";
const { oConfiguracion } = useConfiguracion();
const ordenVentaStore = useOrdenVentaStore();
const { props: props_page } = usePage();
const user = ref(props_page.auth?.user);
</script>
<template>
    <!-- BEGIN #footer -->
    <div id="footer" class="footer">
        <!-- BEGIN container -->
        <div class="container">
            <!-- BEGIN row -->
            <div class="row">
                <div class="col-lg-3"></div>
                <!-- BEGIN col-3 -->
                <div class="col-lg-3">
                    <img
                        :src="oConfiguracion.url_logo"
                        alt=""
                        class="img_logo"
                    />
                </div>
                <!-- END col-3 -->
                <!-- BEGIN col-3 -->
                <div class="col-lg-3">
                    <h4 class="footer-header">{{ oConfiguracion.alias }}</h4>
                    <ul class="fa-ul mb-lg-4 mb-0 p-0">
                        <li>
                            <i class="fa fa-fw fa-angle-right"></i>
                            <Link
                                class="text-white"
                                :href="route('portal.index')"
                                >Inicio</Link
                            >
                        </li>
                        <li>
                            <i class="fa fa-fw fa-angle-right"></i>
                            <Link
                                class="text-white"
                                :href="route('portal.productos')"
                                >Productos</Link
                            >
                        </li>
                        <li v-if="user && user.role_id == 2">
                            <i class="fa fa-fw fa-angle-right"></i>
                            <Link
                                class="text-white"
                                :href="route('portal.misSolicitudes')"
                                >Mis solicitudes</Link
                            >
                        </li>
                        <li>
                            <i class="fa fa-fw fa-angle-right"></i>
                            <Link :href="route('portal.miCarrito')"
                                >Mi carrito
                            </Link>
                        </li>
                    </ul>
                </div>
                <!-- END col-3 -->
            </div>
            <!-- END row -->
        </div>
        <!-- END container -->
    </div>
    <!-- END #footer -->
</template>
<style scoped>
.img_logo {
    margin-left: -130px;
    width: 370px;
}

.footer {
    color: white;
    background-color: var(--principal-portal);
    box-shadow: none;
}

@media (max-width: 900px) {
    .img_logo {
        margin-left: 0px;
        width: 270px;
    }
}
</style>
