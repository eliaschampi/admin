import request from "../Http";

export default {
  store: (data) => request.post("/op", data)
};
