import { imagePerson } from "../../Helpers/util";
import { FETCH_TEACHER } from "../types";

export default {
  namespaced: true,
  state: {
    teacher: null
  },
  mutations: {
    [FETCH_TEACHER](state, teacher) {
      state.teacher = teacher;
    },
    UPDATE_PROFILE(state, profile) {
      state.teacher.profile = profile;
    }
  },
  getters: {
    image: ({ teacher }) => imagePerson(teacher.profile, teacher.gender),
    fullname: (state) => {
      if (state.teacher) {
        return `${state.teacher.name} ${state.teacher.lastname}`;
      }
      return "Buscar docente";
    },
    dni: (state) => {
      if (state.teacher === null) {
        return undefined;
      }
      return state.teacher.dni;
    }
  }
};
