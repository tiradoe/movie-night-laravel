import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faBars,
  faPencilAlt,
  faTrashAlt,
  faCogs,
  faSignOutAlt,
  faTimesCircle,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import axiosPlugin from "@/plugins/axios";
import "./index.css";

library.add(
  faBars,
  faPencilAlt,
  faTrashAlt,
  faCogs,
  faSignOutAlt,
  faTimesCircle
);

const app = createApp(App);
app.use(store);
app.use(router);
app.use(axiosPlugin);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount("#app");
