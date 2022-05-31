<template>
  <div>
    <m-table
      :columns="columns"
      :load="loading"
      :head="false"
      :data="attentions"
    >
      <template v-slot:data="{ rows }">
        <tr v-for="item in rows" :key="item.code">
          <td>{{ item.code }}</td>
          <td>{{ item.user.name }}</td>
          <td>
            {{ item.title }}
          </td>
          <td>
            <span class="badge badge-primary">
              {{ item.entity_identifier === dni ? "No" : "Si" }}
            </span>
          </td>
          <td>
            {{ item.created_at | datetim }}
          </td>
          <td>
            <m-action />
            <m-action icon="print" color="success" />
          </td>
        </tr>
      </template>
    </m-table>
  </div>
</template>
<script>
import api from "../../Api/attention";
export default {
  name: "attention-student",
  data() {
    return {
      attentions: [],
      loading: false,
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
  mounted() {
    this.fetchData();
  },
  watch: {
    "$route.params.dni"(val) {
      this.fetchData(val);
    }
  },
  computed: {
    dni() {
      return this.$route.params.dn;
    }
  },
  methods: {
    async fetchData(val = this.dni) {
      this.loading = true;
      const { data } = await api.fetchByEntity(val);
      this.attentions = data.values;
      this.loading = false;
    }
  }
};
</script>