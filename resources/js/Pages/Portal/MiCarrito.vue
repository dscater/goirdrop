<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { onMounted, ref, inject } from "vue";
import { useOrdenVentaStore } from "@/stores/ordenVenta/ordenVentaStore";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
const ordenVentaStore = useOrdenVentaStore();
const { oConfiguracion } = useConfiguracion();
const carrito = ref(ordenVentaStore.getCarrito());
onMounted(() => {
    ordenVentaStore.initCarrito();
    carrito.value = ordenVentaStore.getCarrito();
});
</script>
<template>
    <div id="product" class="section-container pt-20px">
        <div class="container">
            <div class="row">
                <div class="col-12 overflow-auto">
                    <table class="table table-striped">
                        <thead>
                            <tr class="bg-dark">
                                <th class="text-white">#</th>
                                <th class="text-white" colspan="2">PRODUCTO</th>
                                <th class="text-white text-right">CANTIDAD</th>
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
                                <td class="text-right">{{ item.cantidad }}</td>
                                <td class="text-right">{{ item.precio }}</td>
                                <td class="text-right">{{ item.subtotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.section-container {
    min-height: 70vh !important;
}
</style>
