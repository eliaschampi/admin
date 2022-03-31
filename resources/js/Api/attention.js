import request from "../Http";

export default {
  fetchByMonth: (month, page) =>
    request.get(`/attention/${month}?page=${page}`),

  store: (data) =>
    request.post("/attention", data, {
      "Content-Type": "multipart/form-data"
    }),

  update: (data) => request.put(`/attention/${data.code}`, data),

  del: (code) => request.delete(`/attention/${code}`),

  downloadAttached: (code) =>
    request.get(`/attention_dw/${code}`, { responseType: "blob" }),

  print: (code) =>
    request.get(`/attention_print/${code}`, { responseType: "blob" })
};
