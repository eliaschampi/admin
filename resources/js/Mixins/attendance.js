import api from "../Api/attendance";
/**
 * @name errorQr
 */
export const errorQr = {
  data() {
    return {
      state: "n",
      message: "",
      currentTime: null,
      states: {
        n: "icon ion-md-cloudy text-muted",
        e: "icon ion-md-rainy text-danger",
        s: "icon ion-md-partly-sunny text-warning"
      }
    };
  },
  computed: {
    subtitle() {
      const curHr = new Date().getHours();
      if (curHr < 12) {
        return "Horario Mañana";
      } else if (curHr < 18) {
        return "Horario Tarde";
      } else {
        return "Horario noche";
      }
    }
  },
  methods: {
    onDecode(decodedString) {
      if (decodedString.length !== 8 || this.state !== "n") return;
      this.save(decodedString);
    },
    errorCaptured(error) {
      switch (error.name) {
      case "NotAllowedError":
        this.message = "ERROR: necesita conceder permiso de acceso al scaner";
        break;
      case "NotFoundError":
        this.message = "ERROR: no hay scaner en este dispositivo";
        break;
      case "NotSupportedError":
        this.message = "ERROR: contexto seguro requerido (HTTPS, localhost)";
        break;
      case "NotReadableError":
        this.message = "ERROR: ¿el scaner ya está en uso?";
        break;
      case "OverconstrainedError":
        this.message = "ERROR: las cámaras instaladas no son adecuadas";
        break;
      case "StreamApiNotSupportedError":
        this.message =
            "ERROR: La API de streaming no es compatible con su navegador";
        break;
      }
    }
  }
};

/**
 * @name edit
 */
export const edit = {
  data() {
    return {
      states: {
        presente: "badge badge-success",
        tarde: "badge badge-warning",
        permiso: "badge badge-primary",
        falta: "badge badge-danger",
        "envió justificación": "badge badge-dark",
        justificado: "badge badge-info"
      },
      attendances: [],
      buscado: "",
      mod: 0,
      original: {}
    };
  },
  methods: {
    edit(item) {
      this.mod = item.code;
      this.original = Object.assign({}, item);
    },
    cancelEdit(item) {
      this.mod = 0;
      this.attendances.splice(this.attendances.indexOf(item), 1, this.original);
      this.original = {};
    },
    showJusModal(item) {
      this.original = Object.assign({}, item);
      $("#jus").modal("show");
    },
    update(item) {
      api.update(item).then((r) => {
        this.$snack.success(r.data);
        this.mod = 0;
        this.original = {};
      });
    },
    jusClosed() {
      this.original = {};
      $("#jus").modal("hide");
    },
    jusUpdated(data) {
      this.original = {};
      this.$snack.success(data);
      $("#jus").modal("hide");
      this.fetchData();
    }
  }
};

export const priority = {
  data() {
    return {
      priority: 1
    };
  },
  methods: {
    handleChangePriority() {
      this.priority = this.priority < 3 ? this.priority + 1 : 1;
      this.$snack.show(`Ingreso Nro ${this.priority} ha sido seleccionado`);
      this.fetchData();
    }
  }
};
