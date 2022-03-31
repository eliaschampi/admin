import request from "../Http";

export default {

  store: (data) => request.post("/op", data),

  printCard: (cycle_code) =>
    request.get(`/teacher_card/${cycle_code}`, {
      responseType: "blob"
    })
};
