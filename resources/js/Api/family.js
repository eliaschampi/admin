import request from "../Http";

export default {
  // code is family code
  fetch: (dni) => request.get(`/family/self/${dni}`),

  fetchBySection: (section_code) => request.get(`/family_sec/${section_code}`),

  // where code is family_code
  fetchWork: (dni) => request.get(`/familywork/${dni}`),

  // where data is workData
  updateWork: (data) => request.post("/familywork", data),

  fetchByStudent: (student_dni) => request.get(`/family_s/${student_dni}`),

  addStudent: (data) => request.put("/family_s", data),

  removeStudent: (family_dni, student_dni) =>
    request.delete(`/family_s/${family_dni}/${student_dni}`),

  fetchStudents: (family_dni) => request.get(`/family_st/${family_dni}`),

  printInfo: (family_dni) =>
    request.get(`/family_pdf/${family_dni}`, { responseType: "blob" })
};
