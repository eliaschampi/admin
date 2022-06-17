import request from "@/Http";

export default {
  fetchByType: (type, page) => request.get(`/inspection/${type}?page=${page}`),

  fetchByEntity: (dni, type) =>
    request.get(`/cedp/inspection/${dni}/${type}`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  print: (code) =>
    request.get(`/inspection_print/${code}`, {
      responseType: "blob",
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  fetchStates: () => request.get("/inspection"),

  store: (data) => request.post("/inspection", data),

  update: (data, code) => request.put(`/inspection/${code}`, data),

  destroy: (code) => request.delete(`/inspection/${code}`)
};
