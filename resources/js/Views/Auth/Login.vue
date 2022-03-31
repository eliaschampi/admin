<template>
  <l-form
    :title="$store.getters['myapp']"
    subtitle="Ingrese sus credenciales de autenticación para acceder."
    :error="error"
    :load="load"
    btn="ACCEDER"
    @onsub="mlogin"
  >
    <m-input
      id="Correo"
      v-model="user.email"
      v-validate="'required|email|max:100'"
      :error="errors.first('Correo')"
      placeholder="Ejm. johndoe@aeduca.com"
    >
      <i class="icon ion-md-person text-accent prefix"></i>
    </m-input>
    <m-input
      id="Contraseña"
      type="password"
      v-model="user.password"
      v-validate="'required|min:6|max:50'"
      :error="errors.first('Contraseña')"
      placeholder="*******"
    >
      <i class="icon ion-md-lock text-accent prefix"></i>
    </m-input>
    <router-link
      slot="foot"
      :to="{ name: 'recover' }"
      class="text-center text-accent"
    >
      <i class="icon ion-md-lock mr-3"></i> Olvidé mi contraseña
    </router-link>
  </l-form>
</template>
<script>
import { mapActions } from "vuex";
import Form from "./Form";
export default {
  components: { lForm: Form },
  data() {
    return {
      load: false,
      user: {
        email: "",
        password: ""
      },
      error: ""
    };
  },
  methods: {
    ...mapActions("user", ["login"]),
    mlogin() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.load = true;
          this.error = "";
          this.login(this.user)
            .then(() => {
              this.$router.go(0);
            })
            .catch((error) => {
              if (error.code === 404) {
                this.error = "Usuario no encontrado";
              } else if (error.code !== 500) {
                this.error = error.data.error;
              }
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    }
  }
};
</script>
