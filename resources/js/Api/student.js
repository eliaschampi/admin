import request from "../Http";

export default {
  printInfo: (dni) =>
    request.get(`/student_pdf/${dni}`, { responseType: "blob" }),

  exportToExcel: (year) =>
    request.get(`/alumno_excel/${year}`, { responseType: "blob" }),

  changeBranch: (data) => request.put(`/student/branch/${data.dni}`, data),

  fetchGroupedByGender: () => request.get("/alumno_gender"),

  fetchLatest: () => request.get("/alumno_last")
};
