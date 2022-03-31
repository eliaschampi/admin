export default {
  myapp: (state) => {
    return state.myapp;
  },

  fullyear: (state) => {
    return state.user.user.current_year;
  },
  /**
   * here main getters
   */
  isEmptyRange: (state) => {
    return !(state.range.from && state.range.to);
  },

  range: (state) => state.range,

  isToday() {
    return (date) => {
      return (
        new Date(date).toLocaleDateString() === new Date().toLocaleDateString()
      );
    };
  },

  can(state) {
    return (roles) => {
      const { rol_code, current_year } = state.user.user;
      if (roles.includes("Y")) {
        return roles.includes(rol_code);
      }
      if (current_year !== new Date().getFullYear()) {
        return false;
      }
      return roles.includes(rol_code);
    };
  },

  branches(state) {
    if (!state.sfm_ops.only_current_branch && state.sfm_ops.who === "student") {
      return state.branches;
    }
    return [];
  }
};
