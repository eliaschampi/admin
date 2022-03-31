import request from "../Http";

export default {
  fetchMain: (section_code) => request.get(`/schedule/${section_code}`),
  fetchByOp: (op_code) => request.get(`/schedule/op/${op_code}`),
  set: (data) => request.post("/schedule", data),
  update: (data) => request.put(`/schedule/${data.code}`, data),
  del: (code) => request.delete(`/schedule/${code}`)
};
