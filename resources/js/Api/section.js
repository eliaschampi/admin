import request from "../Http";

export default {
  // code is degree_code
  fetchSumary: () => request.get("/section"),
  // by degree
  fetchByDegree: (code) => request.get(`/section_dg/${code}`),
  // code is degree_code
  fetchForMigrate: (code) => request.get(`/migrate/${code}`),
  // ucknow method
  set: (data) => request.post("/section", data),
  // code is section_code
  update: (data, section_code) => request.put(`/section/${section_code}`, data),
  // code is section_code
  del: (code) => request.delete(`/section/${code}`)
};
