import cycle from "@/Api/cycle";
import { FETCH_CYCLE, FETCH_CYCLES, SET_CURRENT } from "../types";

export default {
  namespaced: true,
  state: {
    cycles: [],
    cycle: {
      type: "",
      title: "",
      from: "",
      to: "",
      entry_time: "07:45:00",
      tolerance: 5,
      monthly: ""
    },
    current: ""
  },
  mutations: {
    [FETCH_CYCLES](state, { cycles }) {
      state.cycles = cycles;
    },
    [FETCH_CYCLE](state, { cycle }) {
      state.cycle = cycle;
    },
    [SET_CURRENT](state, { current }) {
      state.current = current;
    }
  },
  actions: {
    fetchAll({ state, commit }, cycle_code = "") {
      return new Promise((resolve) => {
        if (state.cycles.length === 0) {
          cycle.fetchAll().then((r) => {
            const { values: cycles } = r.data;
            commit(FETCH_CYCLES, { cycles });
            if (state.current === "" && cycles.length > 0) {
              if (cycle_code === "") {
                cycle_code = cycles[0].code;
              }
              commit(SET_CURRENT, { current: cycle_code });
            }
            resolve(true);
          });
        } else {
          resolve(true);
        }
      });
    },
    set(context, data) {
      return new Promise((resolve) => {
        cycle.set(data).then((r) => {
          resolve(r.data);
        });
      });
    },
    update(context, data) {
      return new Promise((resolve) => {
        cycle.update(data).then((r) => {
          resolve(r.data);
        });
      });
    }
  }
};
