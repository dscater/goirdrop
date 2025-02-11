import { defineStore } from "pinia";

export const useOrdenVentaStore = defineStore("ordenVenta", {
    state: () => ({
        ordenVenta: {},
    }),
    actions: {
        setOrdenVenta(value) {
            this.ordenVenta = value;
        },
    },
});
