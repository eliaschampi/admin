/**
 * @author Elias Champi
 * we import vue and vuex for our project
 */

import Vue from "vue";
import Vuex from "vuex";
/**
 * now, we import modules
 */
import user from "./modules/user";
import cycle from "./modules/cycle";
import degree from "./modules/degree";
import teacher from "./modules/teacher";
import student from "./modules/student";
import family from "./modules/family";
import section from "./modules/section";
import register from "./modules/register";
/**
 * import global actions,mutations,getters
 */
import actions from "./actions";
import mutations from "./mutations";
import getters from "./getters";
import { mym } from "../Helpers/date";

/**
 * we use vuex here
 */
Vue.use(Vuex);

/**
 * state of my vue store
 */
const state = {
  myapp: `${window.Laravel.appName} ${window.Laravel.version}`,
  version: window.Laravel.version,
  ubigeo: [],
  cats: [],
  detail: {
    actiontype: "",
    topay: "0.00",
    paid: "0.00",
    month: ""
  },
  date: "",
  month: mym(),
  range: {
    from: "",
    to: ""
  },
  branches: [],
  sfm_ops: {
    who: "family",
    mode: 1,
    only_current_reg: true,
    only_current_branch: true
  }
};

/**
 * we create a new store instance
 */
export default new Vuex.Store({
  state,
  mutations,
  actions,
  modules: {
    user,
    cycle,
    degree,
    student,
    teacher,
    section,
    register,
    family
  },
  getters
});
