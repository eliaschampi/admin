<template>
  <card
    :title="'Registro de Incidencias del Año ' + $store.getters['fullyear']"
  >
    <template slot="rb">
      <slot name="report"></slot>
      <m-router
        v-can="'NP'"
        :to="{ name: 'new_incidence' }"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-md-add icon-md"
      />
    </template>
    <div class="row">
      <m-table
        class="col-md-12"
        :columns="columns"
        :data="filtered"
        :fetch="fetchData"
        :load="loading"
        v-model="buscado"
      >
        <div class="d-flex">
          <month @monthchange="fetchData" style="max-width: 9rem" />
          <m-check
            id="show_siseve_id"
            class="ml-3 align-self-center"
            text="Mostrar Incidencias SISEVE"
            v-model="show_siseve"
          />
        </div>
        <template v-slot:data="{ rows }">
          <tr :key="item.code" v-for="item in rows">
            <td>{{ item.code }}</td>
            <td>{{ item.user.name }}</td>
            <td style="max-width: 10rem">
              {{ types[item.type] }}
              <div class="badge badge-primary" v-show="item.is_siseve">
                SISEVE
              </div>
            </td>
            <td>{{ item.created_at | datetim }}</td>
            <td style="max-width: 10rem">
              <p>{{ item.title }}</p>
            </td>
            <td class="font-weight-medium text-primary">
              <i class="icon ion-md-people icon-sm text-accent"></i>
              <template v-if="item.persons.length === 1">
                {{ `${item.persons[0].name} ${item.persons[0].lastname}` }}
              </template>
              <template v-else> Varios </template>
            </td>
            <td>
              <template v-if="item.user_code === u_code">
                <m-action @action="edit(item, 'incidence')" />
                <m-action
                  @action="delI(item)"
                  icon="trash"
                  color="danger"
                  tool="Eliminar"
                />
              </template>
              <m-action
                @action="print(item.code)"
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
import in_types from "../../Data/in_types.json";
import Month from "../../Components/Views/Month.vue";
import { inat } from "../../Mixins/utils";
export default {
  components: {
    Month
  },
  mixins: [inat],
  data() {
    return {
      months,
      show_siseve: false,
      columns: [
        "N° de Inc.",
        "Usuario",
        "Tipo",
        "Fecha",
        "Incidencia",
        "Estudiante",
        "Acciones"
      ],
      types: in_types,
      buscado: "",
      incidences: [],
      pagination: {}
    };
  },
  computed: {
    filtered() {
      const tester = new RegExp(this.buscado, "i");
      return this.incidences.filter((item) => {
        return tester.test(item.title) && item.is_siseve === this.show_siseve;
      });
    }
  },
  methods: {
    async fetchData(page = 1) {
      const month = this.$store.state.month;
      const { data } = await api.fetchByMonth(month, page);
      const all = data.values;
      const vals = all.data.map((item) => {
        const persons = item.persons.map((p) => ({
          dni: p.dni,
          name: p.name,
          lastname: p.lastname,
          entity_type: p.pivot.entity_type,
          actor_type: p.pivot.actor_type
        }));
        return {
          persons,
          code: item.code,
          user: item.user,
          user_code: item.user_code,
          title: item.title,
          type: item.type,
          description: item.description,
          agreement: item.agreement,
          image_attached: item.image_attached,
          is_siseve: item.is_siseve,
          is_visible: item.is_visible,
          created_at: item.created_at
        };
      });
      this.incidences = vals;
      delete all.data;
      this.pagination = all;
    },
    async print(code) {
      const { data } = await api.print(code);
      this.$downl(data, `Incidencia Nro ${code}`);
    },
    delI(item) {
      this.$snack.show({
        text: "¿Está seguro de eliminar la incidencia?",
        button: "CONFIRMAR",
        action: async () => {
          const { data } = await api.del(item.code);
          this.$snack.success(data.message);
          this.incidences.splice(this.incidences.indexOf(item), 1);
        }
      });
    }
  }
};
</script>
