<template>
  <modal
    btn-name="Seleccionar"
    id="customerModal"
    title="Clientes Frecuentes"
    @opened="onOpened"
    @sub="$emit('selected', customer)"
  >
    <div class="row">
      <div class="form-group col-sm-12">
        <label for="cliselect">Elija un Cliente</label>
        <select class="form-control" id="cliselect" v-model="customer">
          <option :key="item.code" v-for="item in customers" :value="item">
            {{ item.name }}
          </option>
        </select>
      </div>
    </div>
  </modal>
</template>

<script>
import api from "../../Api/customer";
export default {
  name: "Customer",
  data() {
    return {
      customers: [],
      customer: {}
    };
  },
  methods: {
    onOpened() {
      api.fetchAll().then((r) => {
        this.customers = r.data.values;
      });
    }
  }
};
</script>
