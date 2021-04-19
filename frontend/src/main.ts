import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faPencilAlt,
    faTrashAlt,
    faUserCircle,
    faSignOutAlt,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import axiosPlugin from "@/plugins/axios";
import "./index.css";

library.add(faPencilAlt, faTrashAlt, faUserCircle, faSignOutAlt);

const app = createApp(App);
app.use(store);
app.use(router);
app.use(axiosPlugin);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount("#app");
