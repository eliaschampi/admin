import request from "../Http";

export default {
  fetchByMonth: (month, page) =>
    request.get(`/attention/${month}?page=${page}`),

  fetchByEntity: (dni) =>
    request.get(`/cedp/attention/${dni}/true`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  store: (data) =>
    request.post("/attention", data, {
      "Content-Type": "multipart/form-data"
    }),

  update: (data) => request.put(`/attention/${data.code}`, data),

  del: (code) => request.delete(`/attention/${code}`),

  print: (code) =>
    request.get(`/attention_print/${code}`, {
      responseType: "blob",
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  downloadAttached: (code) =>
    request.get(`/attention_dw/${code}`, {
      responseType: "blob",
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    })
};
