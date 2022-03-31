<template>
  <modal
    id="reauth"
    :disabled="!password"
    :title="title"
    btn-name="Aceptar"
    @sub="oncheck"
  >
    <p class="text-center">
      <b>{{ name }} </b>, debemos asegurarnos de que eres tú.
    </p>
    <m-input
      id="Contraseña"
      type="password"
      label="Ingresa tu contraseña"
      v-model="password"
    >
      <i class="icon ion-md-lock prefix"></i>
    </m-input>
    <div v-show="error" class="alert alert-danger">{{ error }}</div>
  </modal>
</template>
<script>
import { mapState } from "vuex";
import { EventBus } from "../../Helpers/bus";
import cache from "../../Helpers/cache";
import request from "../../Http";
export default {
  name: "Refresh",
  props: {},
  data() {
    return {
      password: "",
      counter: 0,
      error: "",
      title: "",
      who: ""
    };
  },
  mounted() {
    EventBus.$on("error", this.recibeError);
    EventBus.$on("checkauth", this.onShow);
  },
  computed: {
    ...mapState("user", {
      name: (state) => state.user.name,
      email: (state) => state.user.email
    })
  },
  methods: {
    cleanAndExit() {
      cache.cleanAll();
      window.location.href = "/login";
    },
    recibeError(errData) {
      if (errData.code === "offline") {
        this.$snack.show("Se perdió tu conexión a internet");
      } else if ([500, 429, 403].indexOf(errData.code) !== -1) {
        this.$snack.danger("Ocurrió un error inesperado!");
      } else if (errData.message.substr(-7) === "expired") {
        this.onShow("Se requiere autenticación", "/auth/refresh");
      } else {
        this.cleanAndExit();
      }
    },
    onShow(title, who) {
      this.title = title;
      this.who = who;
      if (this.counter < 4) {
        $("#reauth").modal("show");
      } else if (this.counter < 5) {
        this.$snack.warning("Se ha detectado varios intentos de acceso");
        this.cleanAndExit();
      }
      this.counter++;
    },
    oncheck() {
      const { email, password, who } = this;
      if (!password) return;
      request
        .post(who, {
          email,
          password
        })
        .then(({ data: { valid, hasuser, user } }) => {
          if (valid) {
            if (hasuser) {
              this.$store.dispatch("updateUserCached", user);
            } else {
              EventBus.$emit("reauthed");
            }
            this.error = "";
            $("#reauth").modal("hide");
            this.counter = 0;
          } else {
            this.error = `Contraseña incorrecta. Te quedan ${
              5 - this.counter
            } opciones`;
            if (this.counter >= 5) {
              this.cleanAndExit();
            }
            this.counter++;
          }
        })
        .finally(() => {
          this.password = "";
        })
        .catch(() => {
          this.cleanAndExit();
        });
    }
  },
  beforeDestroy() {
    EventBus.$off("checkauth", this.onShow);
    EventBus.$off("error", this.recibeError);
  }
};
</script>
