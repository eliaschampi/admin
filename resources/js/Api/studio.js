import request from "../Http";

export default {
  fetch: (student_dni) => request.get(`/alumno_st/${student_dni}`),

  set: (data) => request.post("/alumno_st", data),
  // code is ab code
  update: (data) => request.put(`/alumno_st/${data.code}`, data),
  // code is studio code and data is formData
  uploadA: (payload) =>
    request.post("/alumno_st/up", payload, {
      "Content-Type": "multipart/form-data"
    }),
  // code is studio code
  downloadA: (code) =>
    request.get(`/alumno_st/dw/${code}`, { responseType: "blob" }),
  // code is studio code
  del: (code) => request.delete(`/alumno_st/${code}`)
};
