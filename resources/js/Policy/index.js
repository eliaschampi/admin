/**
 * @author Elias Champi Hancco
 */
import cache from "../Helpers/cache";

let rol_code = "";
let year = "";
if (cache.hasThis("user")) {
  const user = cache.getItem("user");
  rol_code = user.rol_code;
  year = user.current_year;
}

const current = new Date().getFullYear();

export default {
  install(Vue) {
    Vue.directive("can", {
      //  v-can="'APNS'"
      //  v-can="'Y'"
      bind(el, binding) {
        // geting user data
        let roles = binding.value;
        if (roles !== "A") roles += "A";
        // should not cantain year
        if (roles.includes("Y")) {
          // if exists y then check if he does not have roles
          if (!roles.includes(rol_code)) {
            el.style.display = "none";
          }
        } else if (year !== current) {
          el.style.display = "none";
        } else if (!roles.includes(rol_code)) {
          // hide this again
          el.style.display = "none";
        }
      }
    });
  }
};
