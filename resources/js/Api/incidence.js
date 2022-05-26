import request from "../Http";

export default {
  fetchByMonth: (month, page) =>
    request.get(`/incidence/${month}?page=${page}`),

  store: (data) =>
    request.post("/incidence", data, {
      "Content-Type": "multipart/form-data"
    }),

  update: (data) => request.put(`/incidence/${data.code}`, data),

  del: (code) => request.delete(`/incidence/${code}`),

  downloadAttached: (code) =>
    request.get(`/incidence_dw/${code}`, {
      responseType: "blob",
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  print: (code) =>
    request.get(`/incidence_print/${code}`, {
      responseType: "blob",
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    })
};
