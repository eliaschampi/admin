<template>
  <card
    :title="'Registro de Incidencias del Año ' + $store.getters['fullyear']"
  >
    <template slot="rb">
      <slot name="report"></slot>
      <m-router
        :to="{ name: 'new_incidence' }"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-md-add icon-md"
      />
    </template>
    <div class="row">
      <m-table
        class="col-md-12"
        :columns="columns"
        :data="incidences"
        :fetch="fetchData"
        v-model="buscado"
      >
        <month slot="tablefilter" @monthchange="fetchData" />
        <template slot="data">
          <tr :key="item.code" v-for="item in filtered">
            <td>{{ item.code }}</td>
            <td>{{ item.user.name }}</td>
            <td style="max-width: 10rem">{{ types[item.type] }}</td>
            <td>{{ item.created_at | datetim }}</td>
            <td style="max-width: 10rem">
              <p>{{ item.title }}</p>
            </td>
            <td class="font-weight-medium text-primary">
              <i class="icon ion-md-people icon-sm text-accent"></i>
              <template v-if="item.students.length === 1">
                {{
                  `${item.students[0].person.name} ${item.students[0].person.lastname}`
                }}
              </template>
              <template v-else> Varios </template>
            </td>
            <td>
              <template v-if="item.user_code === u_code">
                <m-action @action="edit(item)" />
                <m-action
                  @action="delI(item)"
                  icon="trash"
                  color="danger"
                  tool="Eliminar"
                />
              </template>
              <m-action
                @action="print(item)"
                icon="print"
                color="success"
                tool="Imprimir"
              />
            </td>
          </tr>
        </template>
        <div slot="foot" class="d-flex justify-content-center">
          <pagination :data="pagination" @pagination-change-page="fetchData" />
        </div>
      </m-table>
    </div>
    <p slot="foot" class="text-center text-primary">
      Total: {{ incidences.length }} incidencias
    </p>
  </card>
</template>
<script>
import months from "../../Data/months.json";
import api from "../../Api/incidence";
import Month from "../../Components/Views/Month.vue";
import cache from "../../Helpers/cache";
export default {
  components: {
    Month
  },
  data() {
    return {
      months,
      columns: [
        "N° de Inc.",
        "Usuario",
        "Tipo",
        "Fecha",
        "Incidencia",
        "Estudiante",
        "Acciones"
      ],
      types: {
        in: "Incidencia física",
        ve: "Incidencia verbal entre varios",
        co: "Incidencia por conducta",
        me: "Incidencia médica",
        re: "Requisa",
        ot: "Otro"
      },
      buscado: "",
      incidences: [],
      pagination: {}
    };
  },
  mounted() {
    this.fetchData();
  },
  computed: {
    filtered() {
      const tester = new RegExp(this.buscado, "i");
      return this.incidences.filter((item) => {
        return tester.test(item.title);
      });
    },
    u_code() {
      return this.$store.state.user.user.code;
    }
  },
  methods: {
    async fetchData(page = 1) {
      const month = this.$store.state.month;
      const { data } = await api.fetchByMonth(month, page);
      const all = data.values;
      this.incidences = [...all.data];
      delete all.data;
      this.pagination = all;
    },
    print(item) {
      api.print(item.code).then(({ data }) => {
        this.$downl(data, `Incidencia Nro ${item.code}`);
      });
    },
    edit(item) {
      cache.setItem("incidence", item);
      this.$router.push({ name: "new_incidence", params: { code: item.code } });
    },
    delI(item) {
      this.$snack.show({
        text: "¿Está seguro de eliminar la incidencia?",
        button: "CONFIRMAR",
        action: () => {
          api.del(item.code).then(() => {
            this.$snack.success("Correctamente Eliminado");
            this.incidences.splice(this.incidences.indexOf(item), 1); //eslint-disable-line
          });
        }
      });
    }
  }
};
</script>
