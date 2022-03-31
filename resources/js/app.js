/**
 * @author Elias Champi Hancco
 * @name Elias
 * @since 0.5.0
 * @see www.facebook.com/ChampiElias
 */
/* import Vue */
import Vue from "vue";
/* root template */
import App from "./App.vue";
/**
 * vuex store
 */
import store from "./State/store";
/**
 * vue router
 */
import router from "./Router";
/**
 * import policies
 */
import policy from "./Policy";

/**
 * use eventBus
 */
Vue.use(policy);
// import and register global utility filters.
require("./Filters");

/** import utility components */
require("./includes");

/**
 * @name Main
 * @type Object
 * create access point or new vue instance for our aplication
 */
new Vue({
  el: "#app",
  router,
  store,
  render: (app) => app(App)
});
