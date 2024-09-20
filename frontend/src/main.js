import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";

const app = createApp(App);
const pinia = createPinia();

// Global Compenents
import PageTitle from "@/components/ui/PageTitle.vue";
app.component("PageTitle", PageTitle);

// SweetAlert
import swal from "@/services/sweetalert2Service";
app.config.globalProperties.$swal = swal;

app.use(router);
app.use(pinia);
app.mount("#app");

export default createApp;
