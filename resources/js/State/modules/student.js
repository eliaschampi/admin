import { imagePerson } from "../../Helpers/util";
import { FETCH_STUDENT } from "../types";

export default {
  namespaced: true,
  state: {
    student: null
  },
  mutations: {
    [FETCH_STUDENT](state, student) {
      state.student = student;
    },
    UPDATE_PROFILE(state, profile) {
      state.student.profile = profile;
    }
  },
  getters: {
    image: ({ student }) => imagePerson(student.profile, student.gender),
    fullname: (state) => {
      if (state.student) {
        return `${state.student.name} ${state.student.lastname}`;
      }
      return "Buscar estudiante";
    },
    dni: (state) => {
      if (state.student === null) {
        return undefined;
      }
      return state.student.dni;
    }
  }
};
