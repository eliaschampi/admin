import request from "../Http";

export default {
  fetchBySection: ({ section_code, date, priority }) =>
    request.get(`/attendance/${section_code}/${date}/${priority}`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  fetchByEntity: ({ entity_identifier, from, to, priority }) =>
    request.get(
      `/attendance/${entity_identifier}/${from}/${to}/${priority}`,
      {
        headers: {
          "iam-trust": "E_75keseps77_K"
        }
      }
    ),

  fetchForTeacherByDate: (date) => request.get(`/attendance_t/${date}`),

  absences: (date, priority) => request.get(`/attendance_ab/${date}/${priority}`),

  fetchForChart: () => request.get("/attendance_chart"),

  store: (data) => request.post("/attendance", data),

  update: (data) => request.put(`/attendance/${data.code}`, data),

  auto: (data) => request.post("/attendance_auto", data),

  exportToExcel: (entity_identifier, from, to) =>
    request.get(`/attendance_dw/${entity_identifier}/${from}/${to}`, {
      responseType: "blob"
    })
};
