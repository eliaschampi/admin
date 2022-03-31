import request from "../Http";

export default {
  fetchAll: () => request.get("/ciclo"),

  set: (data) => request.post("/ciclo", data),

  print: (code) =>
    request.get(`/reporte/ciclo/${code}`, { responseType: "blob" }),

  update: (data) => request.put(`/ciclo/${data.code}`, data)
};
