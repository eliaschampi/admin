import request from "@/Http";

export default {
  fetchByType: (type) => request.get(`/inspection/${type}`),

  fetchByEntity: (entity_identifier) =>
    request.get(`/inspection/entity/${entity_identifier}`),

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
