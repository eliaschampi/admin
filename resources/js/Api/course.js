import request from "../Http";

export default {
  fetchAll: () => request.get("/curso"),
  set: (data) => request.post("/curso", data),
  update: (data) => request.put(`/curso/${data.code}`, data),
  del: (code) => request.delete(`/curso/${code}`)
};
