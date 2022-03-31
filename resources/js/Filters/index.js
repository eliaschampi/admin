/**
 * @author Elias
 */

import Vue from "vue";
import ctypes from "../Data/cycles.json";
import { dcalendar, dformat, dparseFromFormat } from "../Helpers/day";

/**
 * @name largeDate
 * @description convert date value on LLLL format
 */
Vue.filter("largeDate", (date) => {
  return dformat(date, "DD/MM/YYYY h:mm a");
});

Vue.filter("days", (date) => {
  return dformat(date, "dddd DD");
});

Vue.filter("level", (code) => {
  return ctypes[code.substr(4, 3)];
});

Vue.filter("full", (section_code) => {
  return `${section_code.substr(-2)} de ${ctypes[section_code.substr(4, 3)]}`;
});

Vue.filter("month", (date) => {
  return dformat(date, "dddd DD MMM");
});

Vue.filter("date", (date) => {
  return dformat(date, "DD [de] MMM [del] YYYY");
});
Vue.filter("datetim", (date) => {
  return dformat(date, "DD [de] MMM. h:mm a");
});

Vue.filter("timesimple", (time) => {
  return dformat(time, "h:mm A");
});

Vue.filter("ago", (date) => {
  return dcalendar(date);
});

Vue.filter("time", (date) => {
  if (date !== null) {
    const parsed = dparseFromFormat(date, "HH:mm:ss");
    return parsed.format("h:mm A");
  }
  return "";
});

Vue.filter("currency", (value) => {
  return `S/: ${Number(value).toFixed(2)}`;
});
