import { FETCH_FAMILY } from "../types";

export default {
  namespaced: true,
  state: {
    family: null
  },
  mutations: {
    [FETCH_FAMILY](state, family) {
      state.family = family;
    }
  },
  getters: {
    image: ({ family }) => {
      const t = family.gender === "M" ? "men" : "women";
      return `/default/default_${t}.png`;
    },
    fullname: (state) => {
      if (state.family) {
        return `${state.family.name} ${state.family.lastname}`;
      }
      return "Buscar apoderado";
    },
    dni: (state) => {
      if (state.family === null) {
        return undefined;
      }
      return state.family.dni;
    }
  }
};
