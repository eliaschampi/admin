import request from "../Http";

export function login(data) {
  return request({
    url: "/auth/login",
    method: "post",
    data
  });
}

export function logout() {
  return request({
    url: "/logout",
    method: "post"
  });
}

export function recover(data) {
  return request({
    url: "/auth/recover",
    method: "put",
    data
  });
}

export function check(data) {
  return request({
    url: "/check",
    method: "post",
    data
  });
}
