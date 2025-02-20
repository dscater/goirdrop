<script>
import Portal from "@/Layouts/Portal.vue";
export default {
    layout: Portal,
};
</script>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useApp } from "@/composables/useApp";
import { router, useForm, usePage, Head, Link } from "@inertiajs/vue3";
import { useUser } from "@/composables/useUser";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
const { setLoading } = useApp();
const { oUsuario, limpiarUsuario, setUsuario } = useUsuarios();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const props = defineProps({
    user: {
        type: Object,
    },
});

const url_aux = ref(props.user.url_foto);

const foto = ref(null);
const imagen_cargada = ref(false);

function cargaImagen(e) {
    foto.value = e.target.files[0];
    props.user.url_foto = URL.createObjectURL(foto.value);
    user.value.url_foto = URL.createObjectURL(foto.value);
    imagen_cargada.value = true;
}

const { getUser } = useUser();

function guardarImagen() {
    router.post(
        route("profile.update_foto"),
        { foto: foto.value, _method: "patch" },
        {
            forceFormData: true,
            onSuccess: () => {
                getUser();
                Swal.fire({
                    icon: "success",
                    title: "Correcto",
                    text: `Imagen actualizada`,
                    confirmButtonColor: "#3085d6",
                    // confirmButtonText: `Aceptar`,
                    showConfirmButton: false,
                });
                imagen_cargada.value = false;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },
            onError: (err) => {
                Swal.fire({
                    icon: "info",
                    title: "Error",
                    text: `${
                        flash.error
                            ? flash.error
                            : "Tienes errores en el formulario"
                    }`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                console.log(err);
            },
        }
    );
}

function cancelarImagen() {
    imagen_cargada.value = false;
    props.user.url_foto = url_aux.value;
    user.value.url_foto = url_aux.value;
}

const form = useForm({
    password_actual: "",
    password: "",
    password_confirmation: "",
    _method: "patch",
});

const { props: props_page, flash } = usePage();
const user = ref(props_page.auth?.user);
const url_asset = ref(props_page.url_assets);

const enviaFormulario = () => {
    form.errors = {};
    form.post(route("profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            form.clearErrors();
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `Proceso realizado`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            form.reset();
        },
        onError: (err) => {
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `Tienes errores en el formulario`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            console.log(err);
        },
    });
};

// INFORMACION
let formInfo = ref(null);
const oCliente = ref(null);
const errors = ref([]);

const listExpedido = [
    { value: "LP", label: "La Paz" },
    { value: "CB", label: "Cochabamba" },
    { value: "SC", label: "Santa Cruz" },
    { value: "CH", label: "Chuquisaca" },
    { value: "OR", label: "Oruro" },
    { value: "PT", label: "Potosi" },
    { value: "TJ", label: "Tarija" },
    { value: "PD", label: "Pando" },
    { value: "BN", label: "Beni" },
];

const listUbicacions = [
    { value: "LA PAZ", label: "La Paz" },
    { value: "COCHABAMBA", label: "Cochabamba" },
    { value: "SANTA CRUZ", label: "Santa Cruz" },
    { value: "CHUQUISACA", label: "Chuquisaca" },
    { value: "ORURO", label: "Oruro" },
    { value: "POTOSI", label: "Potosi" },
    { value: "TARIJA", label: "Tarija" },
    { value: "PANDO", label: "Pando" },
    { value: "BENI", label: "Beni" },
];

var url_assets = "";
var url_principal = "";

const validando = ref(false);
const validado = ref(false);

function cargaArchivo(e, key) {
    const file = e.target.files[0];
    if (file) {
        formInfo[key] = file;
    } else {
        formInfo[key] = "";
    }
}
const verTerminosCondiciones = () => {
    modal_dialog_tc.value = true;
};

const submit = () => {
    formInfo.value.post(
        route(
            "clientes.update",
            user.value.cliente ? user.value.cliente.id : 0
        ),
        {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Correcto",
                    text: `Información actualizada`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                setTimeout(() => {
                    // window.location.reload();
                }, 1000);
            },
            onFinish: () => {},
        }
    );
};

const getCliente = () => {
    axios.get(route("profile.getInfoCliente")).then((response) => {
        setUsuario(response.data.user, true, "user");
        formInfo.value = useForm(oUsuario.value);
    });
};

onMounted(() => {
    getCliente();
    url_assets = props.url_assets;
    url_principal = props.url_principal;
});
</script>

<template>
    <Head title="Perfil"></Head>

    <div class="container pt-10px pb-20px section_page">
        <h4 class="section-title clearfix">
            <span class="flex-1"> Mi Perfil </span>
        </h4>
        <div class="row mt-20px">
            <div class="col-md-7">
                <form
                    @submit.prevent="submit()"
                    class="bg-principal-portal2 p-3 login-content"
                    v-if="formInfo"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mt-20px">
                                <input
                                    type="text"
                                    name="nombres"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Nombre(s)"
                                    v-model="formInfo.cliente.nombres"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Nombre(s)*</label
                                >
                            </div>
                            <div
                                class="w-100"
                                v-if="
                                    formInfo.errors &&
                                    formInfo.errors['cliente.nombres']
                                "
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        formInfo.errors["cliente.nombres"]
                                    }}</strong>
                                </span>
                            </div>
                            <div class="form-floating mt-20px">
                                <input
                                    type="text"
                                    name="apellidos"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Apellidos"
                                    v-model="formInfo.cliente.apellidos"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Apellidos*</label
                                >
                            </div>
                            <div
                                class="w-100"
                                v-if="
                                    formInfo.errors &&
                                    formInfo.errors['cliente.apellidos']
                                "
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        formInfo.errors["cliente.apellidos"]
                                    }}</strong>
                                </span>
                            </div>
                            <div class="form-floating mt-20px">
                                <input
                                    type="text"
                                    name="correo"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Correo"
                                    v-model="formInfo.cliente.correo"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Correo*</label
                                >
                            </div>

                            <div
                                class="w-100"
                                v-if="
                                    formInfo.errors &&
                                    formInfo.errors['cliente.correo']
                                "
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        formInfo.errors["cliente.correo"]
                                    }}</strong>
                                </span>
                            </div>
                            <div class="form-floating mt-20px">
                                <input
                                    type="text"
                                    name="cel"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Celular"
                                    v-model="formInfo.cliente.cel"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Celular*</label
                                >
                            </div>

                            <div
                                class="w-100"
                                v-if="
                                    formInfo.errors &&
                                    formInfo.errors['cliente.cel']
                                "
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        formInfo.errors["cliente.cel"]
                                    }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 mt-20px">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="submit"
                            >
                                Actualizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <h4 class="stitle"></h4>
                <div class="row">
                    <div clas="col-12">
                        <div class="info_foto mb-3">
                            <img class="image" :src="user.url_foto" />
                            <br />
                            <label
                                v-if="!imagen_cargada"
                                class="bg-primary btn_principal"
                                for="file_foto"
                                ><b>Cambiar foto</b
                                ><input
                                    type="file"
                                    id="file_foto"
                                    accept="image/png, image/gif, image/jpeg"
                                    hidden
                                    @change="cargaImagen"
                            /></label>
                            <button
                                v-if="imagen_cargada"
                                class="w-50 mb-1 btn btn-dark"
                                @click="guardarImagen"
                            >
                                Guardar cambios
                            </button>
                            <button
                                v-if="imagen_cargada"
                                class="w-50 mb-1 btn btn-white"
                                @click="cancelarImagen"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                <form
                    class="bg-principal-portal2 p-3 login-content"
                    v-if="formInfo"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mt-20px">
                                <input
                                    type="text"
                                    name="usuario"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Nombre de usuario"
                                    v-model="formInfo.correo"
                                    readonly
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Nombre de usuario</label
                                >
                            </div>
                            <div class="form-floating mt-20px">
                                <input
                                    type="password"
                                    name="usuario"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Contraseña actual"
                                    v-model="form.password_actual"
                                    autocomplete="false"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Contraseña actual*</label
                                >
                            </div>
                            <div
                                class="w-100"
                                v-if="form.errors?.password_actual"
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        form.errors.password_actual
                                    }}</strong>
                                </span>
                            </div>
                            <div class="form-floating mt-20px">
                                <input
                                    type="password"
                                    name="usuario"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Nueva contraseña"
                                    v-model="form.password"
                                    autocomplete="false"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Nueva contraseña*</label
                                >
                            </div>
                            <p class="text-white" style="font-size: 0.8em">
                                La contraseña debe tener al menos 8 caracteres,
                                incluyendo una letra mayúscula, un número y un
                                símbolo (@$!%*?&).
                            </p>
                            <div class="w-100" v-if="form.errors?.password">
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{ form.errors.password }}</strong>
                                </span>
                            </div>
                            <div class="form-floating">
                                <input
                                    type="password"
                                    name="usuario"
                                    class="form-control fs-13px h-45px border-0"
                                    placeholder="Repite la contraseña"
                                    v-model="form.password_confirmation"
                                    autocomplete="false"
                                />
                                <label
                                    for="name"
                                    class="d-flex align-items-center text-gray-600 fs-13px"
                                    >Repite la contraseña*</label
                                >
                            </div>
                            <div
                                class="w-100"
                                v-if="form.errors?.password_confirmation"
                            >
                                <span
                                    class="invalid-feedback alert alert-danger"
                                    style="display: block"
                                    role="alert"
                                >
                                    <strong>{{
                                        form.errors.password_confirmation
                                    }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mt-20px">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="enviaFormulario"
                            >
                                Actualizar contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.login-content .form-floating label {
    margin-left: 0px;
}
.info_foto {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.info_foto img.image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: solid 1px gray;
}
</style>
