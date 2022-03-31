import api from "@/Api/degree";
import { FETCH_DEGREES, SET_DEGREE } from "../types";

export default {
  namespaced: true,
  state: {
    degrees: [],
    degree: {}
  },
  mutations: {
    [FETCH_DEGREES](state, { degrees }) {
      state.degrees = degrees;
    },
    [SET_DEGREE](state, degree) {
      state.degree = degree;
    }
  },
  actions: {
    fetch({ commit }, code) {
      return new Promise((resolve, reject) => {
        api
          .fetch(code)
          .then(({ data }) => {
            commit(SET_DEGREE, data.degree);
            resolve(true);
          })
          .catch((error) => {
            if (error.code === 404) {
              reject(error.message);
            }
          });
      });
    },
    fetchAll({ commit, rootState }) {
      return new Promise((resolve) => {
        api.fetchAll(rootState.cycle.current).then((r) => {
          commit(FETCH_DEGREES, { degrees: r.data.values });
          resolve(true);
        });
      });
    }
  },
  getters: {
    exists: (state) => {
      return Object.keys(state.degree).length > 0;
    },
    code: (state) => state.degree.code,
    sections_count: (state) => state.degree.sections_count
  }
};
