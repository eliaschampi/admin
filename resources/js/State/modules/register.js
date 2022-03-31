import register from "@/Api/register";
import { FETCH_REGISTER, FETCH_REGISTERS } from "../types";

export default {
  namespaced: true,
  state: {
    registers: [],
    register: null
  },
  mutations: {
    [FETCH_REGISTER](state, register) {
      state.register = register;
    },
    [FETCH_REGISTERS](state, values) {
      state.registers = values;
    }
  },
  actions: {
    fetchBySection({ commit, rootState }, inactives) {
      return new Promise((resolve) => {
        const s_code = rootState.section.section_code;
        register.fetchBySection(s_code, inactives).then((r) => {
          commit(FETCH_REGISTERS, r.data.values);
          resolve(true);
        });
      });
    },
    fetchRegister({ commit }, data) {
      return new Promise((resolve) => {
        register.fetch(data).then(({ data }) => {
          commit(FETCH_REGISTER, data.value);
          resolve(data.value);
        });
      });
    },
    fetchRegisters(_, dni) {
      return new Promise((resolve) => {
        register.fetchRegisters(dni).then(({ data }) => {
          resolve(data.values);
        });
      });
    },
    del({ state, commit }) {
      return new Promise((resolve) => {
        register.del(state.register.code).then(({ data }) => {
          commit(FETCH_REGISTER, null);
          resolve(data.message);
        });
      });
    },
    //eslint-disable-next-line
    async exportBySection(_, section_code) {
      return await register.exportBySection(section_code);
    }
  }
};
