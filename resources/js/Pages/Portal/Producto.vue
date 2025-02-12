<script>
import Portal from "@/Layouts/Portal.vue";
import SliderImagenes from "@/Components/SliderImagenes.vue";
import Producto from "@/Components/Producto.vue";
export default {
    layout: Portal,
};
</script>
<script setup>
import { usePage, Link } from "@inertiajs/vue3";
const { props: props_page } = usePage();
import { onMounted, ref, inject } from "vue";
const props = defineProps({
    producto: {
        type: Object,
        default: null,
    },
});

const oProducto = ref(props.producto);
onMounted(() => {});
</script>
<template>
    <div id="product" class="section-container pt-20px">
        <div class="container">
            <h4 class="section-title clearfix">
                <span class="flex-1">Ver producto</span>
            </h4>
            <Producto :producto="oProducto"></Producto>
        </div>
    </div>
</template>

<style scoped>
.section-container {
    min-height: 70vh !important;
}
</style>
