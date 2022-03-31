export default {
  install(Vue, atributes) {
    Vue.prototype.$setErrors = function (errorResponse, name = []) {
      //eslint-disable-next-line
      if (!this.hasOwnProperty("$validator")) {
        return;
      }
      this.$validator.errors.clear();
      //eslint-disable-next-line
      if (!errorResponse.hasOwnProperty("errors")) {
        return;
      }
      // my custom field name
      if (name.length > 0) {
        atributes[name[0]] = name[1];
      }

      Object.keys(errorResponse.errors).map((field) => {
        const errorString = errorResponse.errors[field].join(", ");
        this.$validator.errors.add({
          field: atributes[field],
          msg: errorString
        });
      });
    };
  }
};
