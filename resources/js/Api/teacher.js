import request from "../Http";

export default {
  fetch: (dni) => request.get(`/teacher/self/${dni}`),

  fetchAll: (page) => request.get(`/teacher?page=${page}`),

  fetchByCycle: (c_code) => request.get(`/teacher_cycle/${c_code}`),

  fetchBySpe: (spe) => request.get(`/teacher_spe/${spe}`),

  changeState: (dni) => request.put(`/teacher_state/${dni}`),

  printInfo: (dni) =>
    request.get(`/teacher_pdf/${dni}`, { responseType: "blob" })
};
