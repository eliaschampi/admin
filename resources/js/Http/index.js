import axios from "axios";

import NProgress from "nprogress";
import "nprogress/nprogress.css";
import "./nprogress.css";
import cache from "../Helpers/cache";
import { EventBus } from "../Helpers/bus.js";

const request = axios.create({
  baseURL: window.Laravel.apiDomain,
  timeout: 100000
});

request.defaults.headers.common = {
  "X-CSRF-TOKEN": window.Laravel.csrfToken,
  "X-Requested-With": "XMLHttpRequest"
};

request.interceptors.request.use(
  (config) => {
    NProgress.start();
    if (cache.hasThis("user")) {
      config.headers["Authorization"] = `Bearer ${
        cache.getItem("user").access_token
      }`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

request.interceptors.response.use(
  (response) => {
    NProgress.done();
    return response;
  },
  (error) => {
    NProgress.done();

    let errData = {
      message: "No tienes conexion a Internet",
      code: "offline"
    };
    const { response: res } = error;
    if (res) {
      errData = {
        message: res.data.message,
        code: res.status,
        data: res.data
      };
    }

    if ([401, 403, 429, 500].includes(errData.code) || !res) {
      EventBus.$emit("error", errData);
    }
    return Promise.reject(errData);
  }
);

export default request;
