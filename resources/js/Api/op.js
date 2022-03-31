import request from "../Http";

export default {

  store: (data) => request.post("/op", data),

  print: (code) =>
    request.get(`/docente_ciclo/${code}/edit`, { responseType: "blob" }),

  printCard: (cycle_code) =>
    request.get(`/teacher_card/${cycle_code}`, {
      responseType: "blob"
    })
};
