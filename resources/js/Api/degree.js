import request from "../Http";

export default {
  // code is degree-_code
  fetch: (code) => request.get(`/grado/${code}`),
  // code is cycle_code
  fetchAll: (code) => request.get(`/grados/${code}`)
};
