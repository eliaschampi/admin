import request from "@/Http";

export default {
  fetchByDate: (data) => request.get(`/income/${data.from}/${data.to}`),

  fetchDetailByIncome: (code) => request.get(`/income_detail/${code}`),

  fetchCanceleds: () => request.get("/income/canceled"),

  fetchInvoiceNumber: (type) => request.get(`/serie/${type}`),

  store: (data) => request.post("/income", data),

  canceled: (data) => request.put(`/income/${data.code}`, data),

  fetchStudentPayments: (register_code) =>
    request.get(`/payments/${register_code}`, {
      headers: {
        "iam-trust": "E_75keseps77_K"
      }
    }),

  showIncomeDetailFromCache: () => request.get("/income_detail"),

  storeIncomeDetailInCache: (detail) => request.put("/income_detail", detail),

  removeIncomeDetailFromTheCache: (id) => request.put(`/income_detail/${id}`),

  removeAllFromCache: () => request.delete("/income_detail"),

  printStudentPayments: (data) =>
    request.get(`/pagos/${data.register_code}/${data.name}`, {
      responseType: "blob"
    }),

  exportToExcel: (data) =>
    request.get(`/income_excel/${data.from}/${data.to}`, {
      responseType: "blob"
    })
};
