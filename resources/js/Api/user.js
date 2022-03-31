import request from "../Http";

export default {
  fetch: (code) => request.get(`/usuario/${code}`),

  fetchAll: () => request.get("/usuario"),

  fetchRoles: () => request.get("/roles"),

  set: (data) => request.post("/usuario", data),

  update: (data) => request.put(`/usuario/${data.code}`, data),

  changeBranch: (branch) => request.put(`/usuario_branch/${branch}`),

  changeState: (data) => request.put(`/usuario_state/${data.code}`, data),

  changeCurrentYear: (year) =>
    request.put("/usuario_year", {
      year
    }),

  del: (code) => request.delete(`/usuario/${code}`),

  changePassword: (data) =>
    request.put(`/usuario/password/${data.user_code}`, data)
};
