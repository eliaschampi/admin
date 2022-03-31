import {
  FETCH_MAIN,
  SET_BRANCHES,
  SET_UBIGEO,
  UPDATE_SFM,
  SET_CATS
} from "./types";
import api from "../Api/main";
import cache from "../Helpers/cache";
import cashApi from "../Api/cash";
import catApi from "../Api/cat";
import branchApi from "../Api/branch";
import incomeApi from "../Api/income";
export default {
  fetchUbigeo({ commit }) {
    if (cache.hasThis("ubigeo")) {
      commit(SET_UBIGEO, {
        ubigeo: cache.getItem("ubigeo")
      });
    } else {
      api.fetchUbigeo().then((res) => {
        const newubi = res.data.values;
        cache.setItem("ubigeo", newubi);
        commit(SET_UBIGEO, {
          ubigeo: newubi
        });
      });
    }
  },
  fetchMain({ commit }) {
    api.fetchMain().then((r) => {
      commit(FETCH_MAIN, {
        data: r.data.counts
      });
    });
  },
  getCash(context, date = "") {
    return cashApi.fetch(date).then((r) => {
      return r.data.cash;
    });
  },
  fetchInvoiceNumber(context, type) {
    const name = `in_${type}`;
    return new Promise((resolve) => {
      if (cache.hasThis(name)) {
        resolve(cache.getItem(name));
      } else {
        incomeApi.fetchInvoiceNumber(type).then((r) => {
          cache.setItem(name, r.data.number);
          resolve(r.data.number);
        });
      }
    });
  },
  updateUserCachedProps({ state, commit }, args) {
    const user = { ...state.user.user, ...args };
    cache.removeItem("counts");
    cache.setItem("user", user);
    commit("user/FETCH_USER", user);
  },
  updateUserCached({ commit }, user) {
    cache.removeItem("counts");
    cache.setItem("user", user);
    commit("user/FETCH_USER", user);
  },
  updateSfmOption(
    { commit, dispatch },
    { only_current_reg = true, only_current_branch = true, mode, who, after }
  ) {
    if (who === "student" && only_current_branch === false) {
      dispatch("fetchBranches");
    }
    commit(UPDATE_SFM, { only_current_reg, only_current_branch, mode, who });
    after();
  },
  fetchBranches({ state, commit }) {
    if (state.branches.length === 0) {
      branchApi.fetchAll().then(({ data }) => {
        commit(SET_BRANCHES, data.values);
      });
    }
  },
  fetchCats({ state, commit }) {
    if (state.cats.length === 0) {
      catApi.fetch("Ingreso").then(({ data }) => {
        commit(SET_CATS, data.values);
      });
    }
  }
};
