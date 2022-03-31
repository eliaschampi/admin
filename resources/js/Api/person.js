import request from "../Http";

export default {
  fetch: (ptype, dni) => request.get(`/person/${ptype}/${dni}`),
  del: (dni) => request.delete(`/person/${dni}`),
  //profile
  storeProfile: (data) => request.post("/profile", data),
  updateProfile: (dni) => request.put(`/profile/${dni}`),
  destroyProfile: (dni) => request.delete(`/profile/${dni}`),
  printProfileInfo: (dni) =>
    request.get(`/profile_pdf/${dni}`, { responseType: "blob" })
};
