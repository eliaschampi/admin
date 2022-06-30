import request from "../Http";

export default {
  fetchByTeacher: (dni) => request.get(`/op/${dni}`),
  store: (data) => request.post("/op", data),
  update: (dni, ops) =>
    request.put(`/op/${dni}`, {
      ops
    }),
  destroy: (dni) => request.delete(`/op/${dni}`)
};
