// this is academic background
import request from "../Http";

export default {
  fetch: (student_dni) => request.get(`/alumno_ac/${student_dni}`),

  set: (data) => request.post("/alumno_ac", data),
  // code is ab code
  update: (data) => request.put(`/alumno_ac/${data.code}`, data),
  // code is academic code and data is formData
  uploadA: (payload) =>
    request.post("/alumno_ac/up", payload, {
      "Content-Type": "multipart/form-data"
    }),
  // code is academic code
  downloadA: (code) =>
    request.get(`/alumno_ac/dw/${code}`, { responseType: "blob" }),
  // code is academic code
  del: (code) => request.delete(`/alumno_ac/${code}`)
};
