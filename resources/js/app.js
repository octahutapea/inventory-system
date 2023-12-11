import './bootstrap';
import { createApp } from 'vue';

import App from './components/app.vue';
import router from './router/index.js';

import "leaflet/dist/leaflet.css"

import VueChartkick from 'vue-chartkick';
import 'chartkick/chart.js';

const app = createApp(App);

app.config.globalProperties.filters = {
    formatMoney(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
        }).format(value);
    }
}

app.use(router);
app.use(VueChartkick).mount("#app");

import Navbar from "./components/Header/Navbar.vue";
import UserSidebar from "./components/Header/UserSidebar.vue";
import Sidebar from "./components/Header/Sidebar.vue";
import AddButton from "./components/Button/AddButton.vue";
import SubmitButton from "./components/Button/SubmitButton.vue";
import ExportButton from "./components/Button/ExportButton.vue";
import ImportButton from "./components/Button/ImportButton.vue";

app.component("navbar", Navbar);
app.component("sidebar", Sidebar);
app.component("user-sidebar", UserSidebar);
app.component("add-button", AddButton);
app.component("submit-button", SubmitButton);
app.component("export-button", ExportButton);
app.component("import-button", ImportButton);
