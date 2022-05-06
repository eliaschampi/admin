<template>
  <card
    :title="`Registro de ${decorated_types[type]} del año ${$store.getters['fullyear']}`"
  >
    <p slot="foot" class="text-center text-primary">
      {{`Hay ${inspections.length} registros`}}
    </p>
  </card>
</template>
<script>
import api from "../../Api/inspection";
export default {
  name: "Inspection",
  data() {
    return {
      inspections: [],
      type: "p",
      decorated_types: {
        p: "permisos",
        l: "llamadas",
        r: "requisas"
      },
      loading: false
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.loading = true;
      const { data } = await api.fetchByType(this.type);
      this.inspections = data.values;
      this.loading = false;
    }
  }
};
</script>