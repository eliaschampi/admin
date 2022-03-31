import request from "../Http";

export default {
  fetchAll: (student_dni) => request.get(`/antecedente/alumno/${student_dni}`),

  set: (data) => request.post("/antecedente/alumno", data),

  update: (data) => request.put(`/antecedente/alumno/${data.code}`, data),

  del: (code) => request.delete(`/antecedente/alumno/${code}`)
};
