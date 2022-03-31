<template>
  <modal
    :disabled="dis"
    :withForm="false"
    @opened="onOpened"
    @save="add"
    id="district"
    title="Elija el Distrito"
  >
    <div class="form-row mx-2">
      <div class="form-group col-sm-6">
        <label for="depmodal">Departamento</label>
        <select class="form-control" id="depmodal" v-model="depa">
          <option :key="item.code" v-for="item in ubigeo" :value="item">
            {{ item.name }}
          </option>
        </select>
      </div>
      <div class="form-group col-sm-6">
        <label for="provmodal">Provincia</label>
        <select class="form-control" id="provmodal" v-model="prov">
          <option :key="item.code" v-for="item in depa.provs" :value="item">
            {{ item.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="form-row mx-2">
      <div class="form-group col-sm-6">
        <label for="dismodal">Distrito</label>
        <select class="form-control" id="dismodal" v-model="district">
          <option :key="item.code" v-for="item in prov.dists" :value="item">
            {{ item.name }}
          </option>
        </select>
      </div>
    </div>
  </modal>
</template>
<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      departments: [],
      depa: {},
      prov: {},
      district: {}
    };
  },
  computed: {
    ...mapState(["ubigeo"]),
    dis() {
      return !this.$objectHasRow(this.district);
    }
  },
  methods: {
    add() {
      this.$emit("add", this.district);
    },
    onOpened() {
      if (this.ubigeo.length === 0) {
        this.$store.dispatch("fetchUbigeo");
      }
    }
  }
};
</script>
