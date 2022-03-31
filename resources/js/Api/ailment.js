import request from "../Http";

export default {
  fetchByStudent: (student_dni) => request.get(`/ailment/${student_dni}`),

  set: (data) => request.post("/ailment", data),

  update: (data) => request.put(`/ailment/${data.code}`, data),

  del: (code) => request.delete(`/ailment/${code}`),

  uploadA: (formData) =>
    request.post("/ailment_up", formData, {
      "Content-Type": "multipart/form-data"
    }),

  downloadA: (code) =>
    request.get(`/ailment_dw/${code}`, { responseType: "blob" })
};
