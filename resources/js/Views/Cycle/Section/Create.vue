<template>
  <m-form @save="save" btn-name="Guardar" title="Registrar una nueva sección">
    <div class="row">
      <div class="col-md-6">
        <alert :dismisable="false" title="Importante!" type="alert-warning">
          Las secciones se registran según a la cantidad de estudiantes del
          grado anterior, para ello es recomendable
          <router-link
            :to="{
              name: 'migrate_student',
              params: { degree_code: degree.code }
            }"
            class="alert-link my-0"
            >Migrar del año anterior</router-link
          >
        </alert>
      </div>
      <div class="col-md-6">
        <p class="text-muted">
          <i>
            El nombre de la Sección proporcionado no deberá existir en el Grado
            Actual
          </i>
        </p>
        <m-input
          id="Seccion"
          v-model="section.name"
          v-validate="'required|alpha|max:1'"
          :error="errors.first('Seccion')"
          label="Nombre de la Sección"
        />
      </div>
    </div>
  </m-form>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  data() {
    return {
      section: {}
    };
  },
  computed: {
    ...mapState("degree", ["degree"])
  },
  methods: {
    ...mapActions("section", ["set"]),
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          const {
            degree: { code: dcode },
            section
          } = this;
          const name = section.name.toUpperCase();
          this.set({
            code: dcode + name,
            name,
            degree_code: dcode
          })
            .then((r) => {
              this.$snack.success(r);
              this.$router.push({
                name: "sumary",
                params: {
                  degree_code: dcode
                }
              });
            })
            .catch((error) => {
              if (error.code === 422) {
                this.$setErrors(error.data, ["name", "Seccion"]);
              }
            });
        }
      });
    }
  }
};
</script>
