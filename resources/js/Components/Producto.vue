<script>
import SliderImagenes from "@/Components/SliderImagenes.vue";
</script>
<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { onMounted, ref, inject, computed, onBeforeUnmount, watch } from "vue";
import { useFormater } from "@/composables/useFormater";
import axios from "axios";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
const { oConfiguracion } = useConfiguracion();
const { getFormatoMoneda } = useFormater();
const { props: props_page } = usePage();
const props = defineProps({
    producto: {
        type: Object,
        default: null,
    },
});

watch(
    () => props.producto,
    (newValue) => {
        oPublicacion.value = newValue;
        swRealizarOferta.value =
            oPublicacion.value.esta_vigente == true ? true : false;
    }
);

onMounted(() => {
});

onBeforeUnmount(() => {});
</script>
<template>
    <div class="product-detail row">
        <!-- BEGIN product-image -->
        <div class="product-image col-md-6">
            <SliderImagenes :imagenes="producto?.imagens"></SliderImagenes>
        </div>
        <!-- END product-image -->
        <!-- BEGIN product-info -->
        <div class="product-info col-md-6">
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam
                accusantium, modi neque, impedit inventore voluptates id
                distinctio architecto beatae facere quisquam debitis aliquam
                fugiat veritatis saepe excepturi incidunt in eaque!
            </p>
        </div>
        <!-- END product-info -->
    </div>
</template>
<style scoped>
.product-info,
.product-image {
    background-color: rgb(255, 255, 255);
    border-bottom: solid 1px;
    border-color: var(--bg_blue_dark);
}

.product-info {
    border-bottom: solid 3px;
    border-color: var(--principal-portal);
}

.fila_detalle {
    margin: auto;
    max-width: 100%;
    position: relative;
}
.product-detail {
    position: relative;
}

.tiempoRestante {
    display: flex;
    flex-direction: column;
    background-color: rgba(0, 179, 45, 0.767);
    color: white;
    text-align: center;
    padding: 5px;
    min-width: 130px;
    width: 20%;
    border-radius: 5px 0px 6px 0px;
    position: absolute;
    left: 0px;
    top: 0px;
    z-index: 500;
}

.contenedor_imagen {
    position: relative;
}

.product_info_imagen {
    border-right: solid 1px rgb(204, 204, 204);
}

.text_info {
    text-align: right;
}

.detalles_principal {
    font-weight: bold;
}

.detalles_principal .col-12 {
    padding: 10px;
}
.tabla_ofertas tbody tr:hover {
    background-color: transparent;
}

.contenedor_detalles p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 900px) {
    .text_info {
        text-align: center;
    }

    .txt_info2 {
        text-align: center;
    }
    .cont_ofertas {
        border-top: solid 1px white;
    }
}
</style>
