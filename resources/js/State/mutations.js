/* mutating ubigeo */
import {
  UPDATE_DETAIL,
  UPDATE_DETAIL_BY_FIELD,
  FETCH_MAIN,
  SET_UBIGEO,
  SET_RANGE,
  SET_DETAIL,
  SET_BRANCHES,
  UPDATE_SFM,
  SET_CATS
} from "./types";

export default {
  [SET_UBIGEO](state, { ubigeo }) {
    state.ubigeo = ubigeo;
  },
  [FETCH_MAIN](state, { data }) {
    state.counts = data;
  },
  [SET_RANGE](state, range) {
    state.range = range;
  },
  [SET_DETAIL](state, { detail }) {
    state.detail = detail;
  },
  [UPDATE_DETAIL](state, detail) {
    state.detail = { ...state.detail, ...detail };
  },
  [UPDATE_DETAIL_BY_FIELD](state, { field, value }) {
    state.detail[field] = value;
  },
  [SET_BRANCHES](state, branches) {
    state.branches = branches.map(({ code, name }) => ({ code, name }));
  },
  [UPDATE_SFM](state, options) {
    state.sfm_ops = options;
  },
  [SET_CATS](state, cats) {
    state.cats = cats;
  }
};
