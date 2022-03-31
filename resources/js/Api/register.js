import request from "../Http";

export default {
  fetchRegisters: (dni) =>
    request.get(`/register_all/${dni}`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  hasOnCache: () => request.get("/register_has_cache"),

  fetch: (data) =>
    request.get(`/register/${data.dni}/${data.states}/${data.mod}`),

  export: () => {
    return request.get("/register_excel", {
      responseType: "blob"
    });
  },
  // where the code is section code
  fetchForAttendance: (code) => request.get(`/register_asis/${code}`),

  fetchBySection: (s_code, inactives) =>
    request.get(`/register/${s_code}/${inactives}`),

  fetchGroupedByBranch: () => request.get("/register_branch"),

  // data is register data will be created
  set: (data) =>
    request.post("/register", data, data.consV ? { responseType: "blob" } : {}),

  storeMany: (data) => request.post("/register_m", data),

  del: (code) => request.delete(`/register/${code}`)
};
