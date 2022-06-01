<template>
  <m-table :columns="columns" :load="loading" :head="false" :data="attentions">
    <template v-slot:data="{ rows }">
      <tr v-for="item in rows" :key="item.code">
        <td>{{ item.code }}</td>
        <td>{{ item.user.name }}</td>
        <td>
          {{ item.title }}
        </td>
        <td>
          <span class="badge badge-primary">
            {{ item.entity_identifier === $route.params.dni ? "No" : "Si" }}
          </span>
        </td>
        <td>
          {{ item.created_at | datetim }}
        </td>
        <td>
          <template v-if="item.user_code === u_code">
            <m-action @action="edit(item, 'attention')" />
            <m-action
              icon="print"
              color="success"
              tool="Exportar"
              @action="print(item.code)"
            />
          </template>
        </td>
      </tr>
    </template>
  </m-table>
</template>
<script>
import api from "../../Api/attention";
import { fetchData } from "../../Mixins";
import { inat } from "../../Mixins/utils";
export default {
  name: "attention-student",
  mixins: [inat, fetchData],
  data() {
    return {
      attentions: [],
      columns: [
        "Nro",
        "Usuario",
        "Atención",
        "Es del apoderado",
        "Fecha de registro",
        "#"
      ]
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      const { data } = await api.fetchByEntity(this.$route.params.dni);
      this.attentions = data.values;
      this.loading = false;
    },
    async print(code) {
      const { data } = await api.print(code);
      this.$downl(data, `Ficha de Atencion N° ${code}`);
    }
  }
};
</script>