import request from "@/Http";

export default {
  fetch: (code) => request.get(`/expense/${code}`),

  fetchByDates: (data) => request.get(`/expense/${data.from}/${data.to}`),

  fetchGrouped: (data) => request.get(`/expense_chart/${data.from}/${data.to}`),

  exportToExcel: (data) =>
    request.get(`/expense_export/${data.from}/${data.to}`, {
      responseType: "blob"
    }),

  store: (data) => request.post("/expense", data),

  update: (data) => request.put(`/expense/${data.code}`, data),

  del: (code) => request.delete(`/expense/${code}`),

  print: (code) =>
    request.get(`/expense_print/${code}`, { responseType: "blob" })
};
