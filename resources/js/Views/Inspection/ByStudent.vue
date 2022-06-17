<template>
  <div>
    <alert class="mb-4" v-show="$route.params.type === 'p'">
      Los permisos y las justificaciónes son enviados desde la plataforma por el
      usuario, aunque también pueden ser generados por los administradores.
      Revisa y gestiona los permisos aquí
    </alert>
    <m-table
      :columns="columns"
      :load="loading"
      :head="false"
      :data="inspections"
    >
      <template v-slot:data="{ rows }">
        <tr v-for="item in rows" :key="item.code">
          <td>{{ item.code }}</td>
          <td>
            <template v-if="item.user">
              {{ item.user.name }}
            </template>
            <i v-else>Enviado desde web app</i>
          </td>
          <td>
            <span :class="`badge badge-${states[item.state].color}`">{{
              states[item.state].label
            }}</span>
          </td>
          <td>
            {{ item.created_at | datetim }}
          </td>
          <td>
            <b>{{ item.additional }}</b>
          </td>
          <td>
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
  </div>
</template>
<script>
import api from "../../Api/inspection";
export default {
  name: "inspection-student",
  data() {
    return {
      inspections: [],
      loading: false,
      states: {}
    };
  },
  mounted() {
    api.fetchStates().then((r) => {
      this.states = r.data.values;
      this.fetchData();
    });
  },
  watch: {
    "$route.params.type"() {
      this.fetchData();
    },
    "$route.params.dni"() {
      this.fetchData();
    }
  },
  computed: {
    columns() {
      const columns = ["Nro", "Usuario", "Estado", "Fecha de registro"];
      if (this.$route.params.type === "p") {
        return [...columns, "Fecha de permiso", "#"];
      }
      return [...columns, "Obj. requisado", "#"];
    }
  },
  methods: {
    async fetchData() {
      this.loading = true;
      const { dni, type } = this.$route.params;
      const { data } = await api.fetchByEntity(dni, type);
      this.inspections = data.values;
      this.loading = false;
    },
    async print(code) {
      const { data } = await api.print(code);
      this.$downl(data, `Informe Nro ${code}`);
    }
  }
};
</script>
