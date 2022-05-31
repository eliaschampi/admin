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
          <m-action @action="edit(item)" />
          <m-action icon="print" color="success" @action="print(item)" />
        </td>
      </tr>
    </template>
  </m-table>
</template>
<script>
import api from "../../Api/incidence";
import in_types from "../../Data/in_types.json";
import cache from "../../Helpers/cache";
export default {
  name: "incidence-student",
  data() {
    return {
      incidences: [],
      loading: false,
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
  mounted() {
    this.fetchData();
  },
  watch: {
    "$route.params.dni"(val) {
      this.fetchData(val);
    }
  },
  methods: {
    async fetchData(val = this.$route.params.dni) {
      this.loading = true;
      const { data } = await api.fetchByEntity(val);
      this.incidences = data.values;
      this.loading = false;
    },
    edit(item) {
      cache.setItem("incidence", item);
      this.$router.push({ name: "new_incidence", params: { code: item.code } });
    },
    print(item) {
      api.print(item.code).then(({ data }) => {
        this.$downl(data, `Incidencia Nro ${item.code}`);
      });
    }
  }
};
</script>