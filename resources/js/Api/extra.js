import request from "../Http";

export default {
  fetch: (student_dni) => request.get(`/extrainfo/${student_dni}`),

  upsert: (data) => request.post("/extrainfo", data)
};
