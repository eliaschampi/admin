<template>
  <div>
    <div class="d-flex align-items-center" v-if="$objectHasRow(degree)">
      <m-button
        @pum="change"
        style="min-width: 45px"
        color="btn-inverse-primary btn-icon"
        icon="icon ion-md-switch"
      >
      </m-button>
      <h5 class="mx-2 mb-0">
        <span class="text-accent">Nivel:</span>
        {{ degree.code | level }}
      </h5>
      <h5 class="mb-0">
        <span class="text-accent">Grado:</span>
        {{ " " + degree.full_name }}
      </h5>
    </div>
    <h5 @click="change" class="form-inline pointer" v-else>
      Selecciona un grado
      <i class="ml-2 icon ion-md-hand"></i>
    </h5>
    <modal
      :disabled="disableChangeModal"
      @sub="goo"
      btn-name="Cambiar"
      id="changeDegree"
      title="Elegir un grado"
    >
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="cymodal">Nivel Academico</label>
          <select
            class="form-control"
            id="cymodal"
            v-model="$store.state.cycle.current"
            @change="fetchDegrees"
          >
            <option :key="item.code" v-for="item in cycles" :value="item.code">
              {{ item.full_name }}
            </option>
          </select>
        </div>
        <div class="form-group col-sm-6">
          <label for="dgmodal">Grado</label>
          <select class="form-control" id="dgmodal" v-model="local">
            <option :key="item.code" v-for="item in degrees" :value="item">
              {{ item.full_name }}
            </option>
          </select>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
export default {
  data() {
    return {
      local: {}
    };
  },
  computed: {
    ...mapState("degree", ["degrees", "degree"]),
    ...mapState("cycle", ["cycles"]),
    disableChangeModal() {
      return (
        !this.$objectHasRow(this.local) || this.local.code === this.degree.code
      );
    }
  },
  methods: {
    ...mapActions({
      fetchDegrees: "degree/fetchAll",
      fetchCycles: "cycle/fetchAll"
    }),
    change() {
      this.fetchCycles().then(() => {
        this.fetchDegrees();
        $("#changeDegree").modal("show");
      });
    },
    goo() {
      $("#changeDegree").modal("hide");
      this.$store.commit("degree/SET_DEGREE", this.local);
      this.$emit("changed");
    }
  }
};
</script>
