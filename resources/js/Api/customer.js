import request from "../Http";

export default {
  fetchAll: () => request.get("/customer"),
  store: (data) => request.post("/customer", data),
  update: (data) => request.put(`/customer/${data.code}`, data),
  del: (code) => request.delete(`/customer/${code}`)
};
