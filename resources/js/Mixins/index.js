/**
 * @name states
 */
export const states = {
  data() {
    return {
      states: {
        a: {
          badge: "badge-success",
          text: "activo"
        },
        i: {
          badge: "badge-accent",
          text: "inactivo"
        },
        f: {
          badge: "badge-info",
          text: "finalizo"
        },
        p: {
          badge: "badge-warning",
          text: "pendiente"
        }
      }
    };
  }
};

export const myptypes = {
  pt: {
    label: "Publicación",
    title: "content",
    date: "created_at",
    color: "badge-primary"
  },
  ss: {
    label: "Sesión",
    title: "sesion",
    date: "s_date",
    color: "badge-success"
  },
  ev: {
    label: "Examen",
    title: "test",
    date: "t_date",
    color: "badge-warning"
  }
};

export const aula = {
  data() {
    return {
      posts: [],
      buscado: "",
      ptypes: myptypes,
      selected: null
    };
  },
  computed: {
    filtered() {
      return this.posts.filter((item) => {
        const tester = new RegExp(this.buscado, "i");
        return tester.test([item.content, item.course, item.sesion].join());
      });
    }
  },
  methods: {
    show(item) {
      $("#sesionModal").modal("show");
      this.selected = item;
    }
  }
};

/**
 * @name fetchData
 */
export const fetchData = {
  mounted() {
    this.fetchData();
  },
  watch: {
    "$route.params.dni"() {
      this.fetchData();
    }
  }
};

/**
 * @name fetchOnWatch
 */
export const fetchOnWatch = {
  watch: {
    "$route.params.dni"() {
      this.fetchData();
    }
  }
};

export const findlabel = {
  computed: {
    placeholder() {
      const arr = {
        student: "estudiante",
        teacher: "docente",
        family: "apoderado"
      };
      return `Buscar ${arr[this.who]}`;
    }
  }
};

export const logout = {
  methods: {
    log() {
      this.$snack.show({
        text: "Estas a punto de salir del sistema",
        button: "Salir Ahora",
        action: () => {
          this.$store.dispatch("user/logout").then(() => {
            window.location.href = "/login";
          });
        }
      });
    }
  }
};
