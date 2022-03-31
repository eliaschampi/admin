/**
 * @name utils
 * @author Elias
 */

/**
 * @param {Object} value
 * @returns Boolean
 */
export const objectHasRow = (value) => Object.keys(value).length > 0;

/**
 * @param {Object} value
 * @returns Boolean
 */
export const defined = (value) => typeof value !== "undefined";

/**
 * @param {Blob} value
 * @param {String} name
 * @param {String} ext default .pdf
 * @return Boolean
 */
export const download = (value, name, ext = ".pdf") => {
  const url = window.URL.createObjectURL(new Blob([value]));
  const link = document.createElement("a");
  link.href = url;
  link.setAttribute("download", name + ext);
  document.body.appendChild(link);
  link.click();
};

export const imagePerson = (profile, gender) => {
  let img = "";
  if (profile) {
    img = profile.image;
  } else {
    const t = gender === "M" ? "men" : "women";
    img = `default_${t}.png`;
  }
  return `/default/${img}`;
};
