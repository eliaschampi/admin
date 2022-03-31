import user from "@/Api/user";
import { login } from "@/Api/auth";
import cache from "../../Helpers/cache";
import { FETCH_ROLES, FETCH_USER, FETCH_USERS } from "../types";
import { logout, recover } from "../../Api/auth";

export default {
  namespaced: true,
  state: {
    users: [],
    user: {},
    roles: []
  },
  mutations: {
    [FETCH_USERS](state, { users }) {
      state.users = users;
    },
    [FETCH_USER](state, user) {
      state.user = user;
    },
    [FETCH_ROLES](state, { roles }) {
      state.roles = roles;
    }
  },
  actions: {
    login({ commit }, data) {
      return new Promise((resolve, reject) => {
        login(data)
          .then((r) => {
            const { access_token, user } = r.data;
            cache.setItem("user", { ...user, access_token });
            commit(FETCH_USER, user);
            resolve(true);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    fetch({ commit }, code) {
      return new Promise((resolve, reject) => {
        user
          .fetch(code)
          .then((r) => {
            commit(FETCH_USER, r.data.user);
            resolve(true);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    fetchAll({ commit }) {
      return new Promise((resolve) => {
        user.fetchAll().then((r) => {
          commit(FETCH_USERS, { users: r.data.users });
          resolve(true);
        });
      });
    },
    fetchRoles({ commit }) {
      return new Promise((resolve) => {
        user.fetchRoles().then((r) => {
          commit(FETCH_ROLES, { roles: r.data.roles });
          resolve(true);
        });
      });
    },
    //eslint-disable-next-line
    set(_, data) {
      return new Promise((resolve, reject) => {
        user
          .set(data)
          .then((r) => {
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    //eslint-disable-next-line
    update(_, data) {
      return new Promise((resolve, reject) => {
        user
          .update(data)
          .then((r) => {
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },

    changeState({ state }, payload) {
      user.changeState(payload).then(() => {
        const item = state.users.find((item) => item.code === payload.code);
        if (item) {
          item.state = payload.state;
        }
      });
    },
    //eslint-disable-next-line
    recover(_, user) {
      return new Promise((resolve, reject) => {
        recover(user)
          .then((r) => {
            resolve(r.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    logout() {
      return new Promise((resolve) => {
        logout().then((r) => {
          cache.cleanAll();
          resolve(r.data.res);
        });
      });
    }
  },
  getters: {
    actives: (state) => {
      return state.users.filter((item) => item.state);
    },
    inactives: (state) => {
      return state.users.filter((item) => !item.state);
    },
    fullname: (state) => {
      return `${state.user.name} ${state.user.lastname}`;
    },
    image: (state) => {
      return `/default/${state.user.image}`;
    },
    rol: ({ user }) => {
      if (user.rol) {
        return user.rol.name;
      }
    },
    branch: (state) => state.user.branch.name,
    branch_code: (state) => state.user.branch.code
  }
};
