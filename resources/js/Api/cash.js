import request from "../Http";

export default {
  fetch: (date) => request.get(`/cash/${date}`),

  fetchByMonth: (month) => request.get(`/cash_month/${month}`),

  fetchLastCash: () => request.get("/cash_last"),

  fetchChart: () => request.get("/cash_chart"),

  exportToExcel: () => request.get("/cash_export", { responseType: "blob" }),

  open: (data) => request.post("/cash", data),

  toggleCash: (code) => request.put(`/cash/${code}`),

  surrender: (data, cash_code) => request.put(`/surrender/${cash_code}`, data)
};
