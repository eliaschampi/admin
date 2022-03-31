import request from "../Http";

export default {
  fetchMain: (section_code) => request.get(`/schedule/${section_code}`),
  fetchByTeacher: (teacher_dni) => request.get(`/schedule/teacher/${teacher_dni}`),
  set: (data) => request.post("/schedule", data),
  update: (data) => request.put(`/schedule/${data.code}`, data),
  del: (code) => request.delete(`/schedule/${code}`)
};
