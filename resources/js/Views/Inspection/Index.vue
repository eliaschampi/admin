<template>
  <card
    :title="`Registro de ${decorated_types[i_type]} del año ${$store.getters['fullyear']}`"
  >
    <m-button
      slot="rb"
      color="btn-inverse-accent"
      class="btn-icon"
      icon="icon ion-md-add"
    >
    </m-button>
    <div class="selectgroup selectgroup-pills">
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="p"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Permisos</span>
      </label>
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="r"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Requisas</span>
      </label>
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="l"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Llamadas</span>
      </label>
    </div>
    
    <p slot="foot" class="text-center text-primary">
      {{ `Hay ${inspections.length} registros` }}
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
      i_type: "p",
      columns: [
        "Nro",
        "Tipo",
        "Usuario",
        "Dest.",
        "Nombre",
        "Fecha de registro"
      ],
      decorated_types: {
        p: "permisos",
        l: "llamadas",
        r: "requisas"
      },
      loading: false
    };
  },
  watch: {
    i_type(val) {
      switch (val) {
        case "p":
          this.columns[6] = "Fecha de permiso";
          this.columns[7] = "Estado";
          break;
        case "r":
          this.columns[6] = "Obj. requisado";
          this.columns[7] = "Estado";
          break;
        case "l":
          this.columns[6] = "Número";
          this.columns[7] = "Estado";
          break;
        default:
          break;
      }
    }
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