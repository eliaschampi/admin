/**
 * @author Elias Champi Hancco
 */
import Vue from "vue";
import VueRouter from "vue-router";

// here i import my handle configuration
import { authorization } from "./authorization";
// here is my list routes
import routes from "./routes";

// get token
// finally vue use the route
Vue.use(VueRouter);

const router = new VueRouter({
  routes,
  mode: "history" // do not use /#/.
});

/**
 * Before a route is resolved we check for
 * the token if the route is marked as
 * requireAuth.
 */
authorization(router);

export default router;
