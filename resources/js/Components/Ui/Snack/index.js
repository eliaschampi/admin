//
// vue PlugIn
//
import Snack from "./SnackBar.vue";

const Default = {
  default: {
    primary: "#7affa4"
  },
  success: {
    primary: "#00ba3b"
  },
  danger: {
    primary: "#ed2d2d"
  },
  warning: {
    primary: "#FF8800"
  },
  background: "#353535",
  text: "#E3E3E3",
  time: 8000,
  position: "bottom",
  close: true
};

let Constructor; //eslint-disable-line
let component = null;
let timeout = null;
let closing = null;
let options = null;
const config = {};
const methods = {
  danger: "danger",
  show: "default",
  success: "success",
  warning: "warning"
};

const sleep = (time) =>
  new Promise((resolve) => setTimeout(() => resolve(), time));

const close = () => {
  component.active = false;
  clearTimeout(timeout);
};

const create = () => {
  document.addEventListener("DOMContentLoaded", () => {
    const div = document.createElement("div");
    div.id = `snackbar-${Date.now()}`;
    document.body.appendChild(div);
    config.config = Default;
    component = new Constructor({
      propsData: config
    });
    component.$on("close", () => close());
    component.$mount(`#${div.id}`);
  });
};

const actions = async (params, theme) => {
  options = params;
  if (component.active && closing) {
    return;
  }
  if (component.active) {
    close();
    closing = true;
    await sleep(400);
    closing = false;
  }
  // i modified this, button clear
  if (typeof options === "string") {
    options = { text: options, button: "" };
  }
  const fn = options.action;
  options.action = async () => {
    if (!fn) {
      return close();
    }
    fn();
    close();
  };
  Object.assign(config.config, Default[theme]);
  Object.assign(component, Object.assign({}, options, { theme }));
  component.active = true;
  timeout = setTimeout(close, Default.time);
};

const $snack = (opt) => {
  const news = {};
  const themes = {};
  opt.methods = opt.methods || [];
  opt.methods.forEach((item) => {
    news[item.name] = item.name;
    themes[item.name] = {
      primary: item.color || Default.default.primary
    };
  });
  Object.assign(Default, themes);
  const all = {};
  const meth = Object.assign({}, methods, news);
  for (const m in meth) {
    all[m] = (params) => actions(params, meth[m]);
  }
  return all;
};

const plugin = {
  install(Vue, options = {}) {
    Object.assign(Default, options);
    Constructor = Vue.extend(Snack);
    Vue.prototype.$snack = $snack(options);
    create();
  }
};

export default plugin;
