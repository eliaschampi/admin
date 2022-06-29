import request from "../Http";

export default {
  fetch: (dni) => request.get(`/teacher/self/${dni}`),

  fetchByState: (state, page) => request.get(`/teacher/all/${state}?page=${page}`),

  fetchBySection: (section_code) =>
    request.get(`/teacher/section/${section_code}`),

  changeState: (dni) => request.put(`/teacher_state/${dni}`),

  printInfo: (dni) =>
    request.get(`/teacher_pdf/${dni}`, { responseType: "blob" })
};
