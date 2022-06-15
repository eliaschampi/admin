<template>
  <m-table :columns="columns" :load="loading" :head="false" :data="incidences">
    <template v-slot:data="{ rows }">
      <tr v-for="item in rows" :key="item.code">
        <td>{{ item.code }}</td>
        <td>{{ item.user.name }}</td>
        <td>
          {{ item.title }}
        </td>
        <td>
          <span class="badge badge-primary">
            {{ types[item.type] }}
          </span>
        </td>
        <td>
          {{ item.created_at | datetim }}
        </td>
        <td>
          <m-action
            v-show="item.user_code === u_code"
            @action="edit(item, 'incidence')"
          />
          <m-action
            icon="print"
            color="success"
            tool="Exportar"
            @action="print(item.code)"
          />
        </td>
      </tr>
    </template>
  </m-table>
</template>
<script>
import api from "../../Api/incidence";
import in_types from "../../Data/in_types.json";
import { fetchData } from "../../Mixins";
import { inat } from "../../Mixins/utils";
export default {
  name: "incidence-student",
  mixins: [inat, fetchData],
  data() {
    return {
      incidences: [],
      types: in_types,
      columns: [
        "Nro",
        "Usuario",
        "Incidencia",
        "Tipo de incidencia",
        "Fecha de registro",
        "#"
      ]
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      const { data } = await api.fetchByEntity(this.$route.params.dni);
      this.incidences = data.values;
      this.loading = false;
    },
    async print(code) {
      const { data } = await api.print(code);
      this.$downl(data, `Incidencia Nro ${code}`);
    }
  }
};
</script>
