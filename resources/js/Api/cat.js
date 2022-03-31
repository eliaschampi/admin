import request from "@/Http";

export default {
  fetch: (mode) => request.get(`/cat/${mode}`),
  store: (data) => request.post("/cat", data),
  update: (data) => request.put(`/cat/${data.code}`, data)
};
