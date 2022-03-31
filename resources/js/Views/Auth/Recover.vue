<template>
  <l-form
    title="Recuperar contraseña"
    subtitle="Ingresa los campos requeridos y solicita tu nueva clave"
    :error="error"
    :load="load"
    btn="ENVIAR"
    @onsub="rev"
  >
    <m-input
      id="DNI"
      v-model="revData.dni"
      v-validate="'required|min:8|max:8'"
      :error="errors.first('DNI')"
    >
      <i class="icon ion-md-person text-accent prefix"></i>
    </m-input>
    <m-input
      id="Clave"
      type="email"
      label="Correo"
      v-model="revData.email"
      v-validate="'required|email|max:100'"
      :error="errors.first('Clave')"
    >
      <i class="icon ion-md-mail text-accent prefix"></i>
    </m-input>
    <router-link
      slot="foot"
      :to="{ name: 'login' }"
      class="text-center text-accent"
    >
      <i class="icon ion-md-person text-accent mr-3"></i>
      Volver a login
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
      revData: {
        dni: "",
        email: ""
      },
      error: ""
    };
  },
  methods: {
    ...mapActions("user", ["recover"]),
    rev() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.load = true;
          this.recover(this.revData)
            .then((r) => {
              this.$snack.show(r);
              this.$router.push("/login");
            })
            .catch((error) => {
              if (error.code === 500) {
                this.error =
                  "Solicitud rechazada. Asegurate de ingresar sus datos correctamente";
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
