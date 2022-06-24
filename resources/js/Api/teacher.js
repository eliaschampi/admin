import request from "../Http";

export default {
  fetch: (dni) => request.get(`/teacher/self/${dni}`),

  fetchAll: (page) => request.get(`/teacher?page=${page}`),

  fetchBySection: (section_code) => request.get(`/teacher/section/${section_code}`),

  fetchBySpe: (spe) => request.get(`/teacher_spe/${spe}`),

  changeState: (dni) => request.put(`/teacher_state/${dni}`),

  printInfo: (dni) =>
    request.get(`/teacher_pdf/${dni}`, { responseType: "blob" })
};
