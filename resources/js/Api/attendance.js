import request from "../Http";

export default {
  fetchBySection: (data) =>
    request.get(`/attendance/${data.section_code}/${data.date}`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  fetchByEntity: (data) =>
    request.get(
      `/attendance/${data.entity_identifier}/${data.from}/${data.to}`,
      {
        headers: {
          "iam-trust": "E_75keseps77_K"
        }
      }
    ),

  fetchForTeacherByDate: (date) => request.get(`/attendance_t/${date}`),

  absences: (date) => request.get(`/attendance_ab/${date}`),

  fetchForChart: () => request.get("/attendance_chart"),

  store: (data) => request.post("/attendance", data),

  update: (data) => request.put(`/attendance/${data.code}`, data),

  auto: (data) => request.post("/attendance_auto", data),

  exportToExcel: (entity_identifier, from, to) =>
    request.get(`/attendance_dw/${entity_identifier}/${from}/${to}`, {
      responseType: "blob"
    })
};
