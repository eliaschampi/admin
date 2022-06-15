import request from "../Http";

export default {
  fetchByEntity: (dni) => request.get(`/cedp/justification/${dni}`),

  downloadAttached: (code) =>
    request.get(`/justification/${code}`, { responseType: "blob" }),

  store: (data) =>
    request.post("/justification", data, {
      "content-type": "multipart/form-data"
    }),

  toggle: (code, aprove) => request.put(`/justification/${code}/${aprove}`)
};
