<template>
  <panel :title="`Comprobantes Anulados ${$store.getters['fullyear']}`">
    <m-table :columns="columns" :data="incomes" :fetch="fetchData">
      <template slot="data">
        <tr v-for="(item, index) in incomes" :key="index">
          <td>{{ item.created_at | datetim }}</td>
          <td>{{ types[item.type] }}</td>
          <td>{{ item.serie }}</td>
          <td>{{ item.name.name }}</td>
          <td>{{ item.total }}</td>
          <td>{{ item.updated_at | time }}</td>
          <td>{{ item.canceled.justification }}</td>
        </tr>
      </template>
    </m-table>
  </panel>
</template>
<script>
import api from "../../Api/income";
import { incomeMod } from "../../Mixins/utils";
export default {
  mixins: [incomeMod],
  data() {
    return {
      load: false,
      incomes: [],
      columns: [
        "Fecha",
        "Comprobante",
        "Correlativo",
        "Razon Social",
        "Total Importe",
        "Hora Anulado",
        "Justificacion"
      ]
    };
  },
  methods: {
    fetchData() {
      this.load = true;
      return api.fetchCanceleds().then((r) => {
        this.incomes = r.data.values;
        this.load = false;
      });
    }
  }
};
</script>
