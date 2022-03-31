<template>
  <form v-on:submit.prevent="get" class="addon-group" style="max-width: 18rem">
    <label for="fecha">Buscar por fecha</label>
    <div class="input-group" :class="{ 'has-danger': errors.has('fecha') }">
      <input
        type="date"
        class="form-control"
        :class="{ 'form-control-sm': issm }"
        id="iddatepick"
        name="fecha"
        v-validate="valiDate"
        v-model="$store.state.date"
      />
      <div class="input-group-append">
        <m-submit
          color="btn-primary"
          :size="issm ? 'btn-sm' : 'btn-md'"
          icon="icon ion-md-search"
        />
      </div>
    </div>
    <span class="small text-danger" v-show="errors.has('fecha')">
      {{ errors.first("fecha") }}
    </span>
  </form>
</template>
<script>
import { iso } from "../../Helpers/date";
export default {
  props: ["issm"],
  name: "Datepick",
  created() {
    if (this.$store.state.date === "") {
      this.$store.state.date = iso();
    }
  },
  computed: {
    valiDate() {
      const user_year = this.$store.getters["fullyear"];
      let date = new Date();
      if (user_year < date.getFullYear()) {
        date = `${user_year}-12-31`;
      } else {
        date.setDate(date.getDate() + 1);
        date = iso(date);
      }
      const adate = `${user_year}-01-01`;
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: date,
        after: adate
      };
    }
  },
  methods: {
    get() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.$emit("fetch");
        }
      });
    }
  }
};
</script>
