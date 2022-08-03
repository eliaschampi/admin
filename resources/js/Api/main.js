import request from "../Http";

export default {
  fetchByTags: (tags) => request.get(`/configuracion/${tags}`),

  fetchByTagsPlucked: (tags) => request.get(`/configuracion_p/${tags}`),

  setCompany: (data) => request.post("/compania", data),

  setConfig: (data) => request.put(`/configuracion/${data.code}`, data),

  sendToSupport: (data) => request.post("/support", data),

  upload: (data) =>
    request.post("/imagen", data, {
      "Content-Type": "multipart/form-data"
    }),

  fetchUbigeo: () => request.get("/distritos"),

  fetchCounts: () => request.get("/counts"),

  printCard: (dni, section_code) =>
    request.get(`/card/${dni}/${section_code}`, {
      responseType: "blob"
    }),

  printCardS: (section_code) =>
    request.get(`/card/${section_code}`, {
      responseType: "blob"
    })
};
