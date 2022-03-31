import section from "@/Api/section";
import {
  FETCH_SECTIONS,
  SET_SECTION_CODE,
  FETCH_SUMARY,
  UPDATE_SUMARY
} from "../types";

export default {
  namespaced: true,
  state: {
    sections: [],
    sumaries: [],
    section_code: null
  },
  mutations: {
    [FETCH_SECTIONS](state, { sections }) {
      state.sections = sections;
    },
    [FETCH_SUMARY](state, { sumaries }) {
      state.sumaries = sumaries;
    },
    [SET_SECTION_CODE](state, section_code) {
      state.section_code = section_code;
    },
    [UPDATE_SUMARY](state, { person, section_code }) {
      const section = state.sumaries.find((item) => item.code === section_code);
      if (section) {
        section.tutor = { person };
      }
    }
  },
  actions: {
    fetchSumary({ commit }) {
      return new Promise((resolve) => {
        section.fetchSumary().then((r) => {
          commit(FETCH_SUMARY, { sumaries: r.data.sumaries });
          resolve(true);
        });
      });
    },
    fetchAll({ commit, state: { section_code } }, dc) {
      return new Promise((resolve) => {
        section.fetchByDegree(dc).then((r) => {
          const sections = r.data.values;
          commit(FETCH_SECTIONS, { sections });
          if (sections.length > 0) {
            const { code } = sections[0];
            if (!section_code || section_code.substr(0, 9) !== dc) {
              commit(SET_SECTION_CODE, code);
            }
          } else {
            commit(SET_SECTION_CODE, null);
          }
          resolve(true);
        });
      });
    },
    set(context, data) {
      return new Promise((resolve, reject) => {
        section
          .set(data)
          .then((r) => {
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    changeTutor({ commit }, { person, section_code }) {
      return new Promise((resolve, reject) => {
        section
          .update({ teacher_dni: person.dni }, section_code)
          .then((r) => {
            commit(UPDATE_SUMARY, { person, section_code });
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    del(context, code) {
      return new Promise((resolve, reject) => {
        section
          .del(code)
          .then((r) => {
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    }
  },
  getters: {
    code: (state) => state.section_code
  }
};
