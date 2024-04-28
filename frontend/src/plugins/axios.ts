import axios from "axios";
import {getCookie} from "@/modules/utilities";

export default {
  // eslint-disable-next-line
  install: (app: any, options: any): void => {
    app.config.globalProperties.$http = axios.create({
      baseURL: `${process.env.VUE_APP_ROOT_API}`,
      withCredentials: true,
    });

    app.config.globalProperties.$http.defaults.headers.common["X-XSRF-TOKEN"] = getCookie("xsrf-token")
  },
};
