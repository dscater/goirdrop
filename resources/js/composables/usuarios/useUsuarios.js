import { onMounted, ref } from "vue";

const oUsuario = ref({
    id: 0,
    usuario: "",
    nombres: "",
    apellidos: "",
    ci: "",
    ci_exp: "",
    correo: "",
    password: "",
    role_id: "",
    sedes_todo: "",
    array_sedes_id: [],
    foto: "",
    fecha_registro: "",
    acceso: 0 + "",
    status: "",
    _method: "POST",
});

export const useUsuarios = () => {
    const setUsuario = (item = null) => {
        if (item) {
            oUsuario.value.id = item.id;
            oUsuario.value.usuario = item.usuario;
            oUsuario.value.nombres = item.nombres;
            oUsuario.value.apellidos = item.apellidos;
            oUsuario.value.ci = item.ci;
            oUsuario.value.ci_exp = item.ci_exp;
            oUsuario.value.correo = item.correo;
            oUsuario.value.password = item.password;
            oUsuario.value.role_id = item.role_id;
            oUsuario.value.sedes_todo = item.sedes_todo;
            oUsuario.value.array_sedes_id = item.array_sedes_id;
            oUsuario.value.foto = "";
            oUsuario.value.fecha_registro = item.fecha_registro;
            oUsuario.value.acceso = item.acceso;
            oUsuario.value.status = item.status;
            oUsuario.value._method = "PUT";
            return oUsuario;
        }
        return false;
    };

    const limpiarUsuario = () => {
        oUsuario.value.id = 0;
        oUsuario.value.usuario = "";
        oUsuario.value.nombres = "";
        oUsuario.value.apellidos = "";
        oUsuario.value.ci = "";
        oUsuario.value.ci_exp = "";
        oUsuario.value.correo = "";
        oUsuario.value.password = "";
        oUsuario.value.role_id = "";
        oUsuario.value.sedes_todo = "";
        oUsuario.value.array_sedes_id = [];
        oUsuario.value.foto = "";
        oUsuario.value.fecha_registro = "";
        oUsuario.value.acceso = 0 + "";
        oUsuario.value.status = "";
        oUsuario.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oUsuario,
        setUsuario,
        limpiarUsuario,
    };
};
