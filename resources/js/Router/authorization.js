/**
 * @author Elias Champi Hancco
 */
import cache from "../Helpers/cache";
/**
 *
 * @param Object router
 * @description this method is for configure the routes access
 */
export const authorization = (router) => {
  /**
   * route before function
   */
  router.beforeEach((to, from, next) => {
    /**
     * routes that does not required authentification
     */
    const noauth = ["login", "not_found", "recover"];
    /**
     * is logged = user has data
     */
    const isLogged = cache.hasThis("user");

    if (!noauth.includes(to.name)) {
      if (isLogged) {
        const { rol_code, current_year: user_year } = cache.getItem("user");
        if (to.name === "login") {
          next({ name: "home" });
          return;
        }
        if (typeof to.meta.iratyc === "undefined") {
          if (user_year !== new Date().getFullYear()) {
            next({ name: "not_auth" });
            return;
          }
        }
        if (typeof to.meta.roles !== "undefined") {
          if (!to.meta.roles.includes(rol_code)) {
            next({ name: "not_auth" });
            return;
          }
        }
        next();
      } else {
        cache.cleanAll();
        next({ name: "login" });
      }
    } else {
      if (to.name === "login") {
        if (isLogged) {
          next({ name: "home" });
          return;
        }
      }
      next();
    }
  });
};
