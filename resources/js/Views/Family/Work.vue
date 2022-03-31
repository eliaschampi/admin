<template>
  <panel title="Información laboral">
    <form @submit.prevent="save">
      <div class="form-row">
        <m-input
          id="Trabajo"
          class="col-md-6"
          v-model="work.name"
          v-validate="'required|min:5|max:100'"
          label="Nombre de la Empresa"
          :error="errors.first('Trabajo')"
        />
        <m-input
          id="Puesto"
          class="col-md-6"
          v-model="work.position"
          v-validate="'required|min:5|max:100'"
          label="Puesto que desempeña"
          :error="errors.first('Puesto')"
        />
      </div>
      <div class="form-row">
        <m-input
          id="Direccion"
          class="col-md-6"
          v-model="work.address"
          v-validate="'min:5|max:100'"
          label="Dirección donde Trabaja"
          :error="errors.first('Direccion')"
        />
        <m-input
          id="Celular"
          class="col-md-6"
          v-model="work.phone"
          v-validate="'min:5|max:30'"
          label="Teléfono o Celular"
          :error="errors.first('Celular')"
        />
      </div>
      <m-submit color="btn-primary">Actualizar</m-submit>
    </form>
  </panel>
</template>
<script>
import { fetchData } from "../../Mixins";
import api from "../../Api/family";
export default {
  name: "FamilyWork",
  mixins: [fetchData],
  data() {
    return {
      work: {}
    };
  },
  methods: {
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.work.family_dni = this.$route.params.dni;
          api.updateWork(this.work).then((r) => {
            this.$snack.success(r.data.message);
          });
        }
      });
    },
    fetchData() {
      api.fetchWork(this.$route.params.dni).then(({ data }) => {
        this.work = data.value || {};
      });
    }
  }
};
</script>
