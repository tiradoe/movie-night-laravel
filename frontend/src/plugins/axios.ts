import axios from "axios";

export default {
  install: (app: any, options: any): void => {
    app.config.globalProperties.$http = axios.create({
      baseURL: `${process.env.VUE_APP_ROOT_API}/api`,
      withCredentials: true,
    });
  },
};
