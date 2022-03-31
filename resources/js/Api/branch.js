import request from "../Http";

export default {
  fetchAll: () => request.get("/sedes"),
  set: (data) => request.post("/sedes", data),
  update: (data) => request.put(`/sedes/${data.code}`, data)
};
