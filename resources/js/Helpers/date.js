/**
 * @param {Number} year
 * @return {Number}
 */

export const totalDays = (from, to) => {
  return (
    (new Date(to).getTime() - new Date(from).getTime()) / (1000 * 3600 * 24)
  );
};

export const daysPassed = (from, to) => {
  const now = new Date();
  const previous = new Date(from);
  if (previous.getFullYear() < now.getFullYear()) {
    return totalDays(from, to);
  }
  if (now < previous) {
    return 0;
  }
  return Math.ceil((now - previous + 1) / 86400000);
};

export const porsentaje = (from, to) => {
  return (daysPassed(from, to) * 100) / totalDays(from, to);
};

/**
 * iso Date
 */
export const iso = (today = new Date()) => {
  const mm = (today.getMonth() + 1).toString().padStart(2, "0");
  const dd = today.getDate().toString().padStart(2, "0");
  return [today.getFullYear(), mm, dd].join("-");
};

/**
 * month
 */
export const mym = () => {
  return (new Date().getMonth() + 1).toString().padStart(2, "0");
};
