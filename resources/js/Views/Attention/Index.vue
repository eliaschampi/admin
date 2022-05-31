<template>
  <card title="Registro de atenciones">
    <template slot="rb">
      <m-router :to="{ name: 'new_attention' }" color="btn-inverse-accent">
        Nueva atención
      </m-router>
    </template>

    <m-table
      :columns="columns"
      :data="filtered"
      :fetch="fetchData"
      :load="loading"
      v-model="buscado"
    >
      <month @monthchange="fetchData" />
      <template v-slot:data="{ rows }">
        <tr :key="item.code" v-for="item in rows">
          <td>{{ item.code }}</td>
          <td>{{ item.user.name }}</td>
          <td>{{ item.created_at | datetim }}</td>
          <td>
            {{ item.type === "p" ? "Presencial" : "Virtual" }}
          </td>
          <td style="max-width: 10rem">{{ item.title }}</td>
          <td>{{ ptypes[item.entity_type].label }}</td>
          <td>
            <i class="icon ion-md-people icon-sm text-accent"></i>
            <router-link
              class="font-weight-bold text-primary"
              :to="{
                name: ptypes[item.entity_type].route,
                params: { dni: item.person.dni }
              }"
            >
              {{ `${item.person.name} ${item.person.lastname}` }}
            </router-link>
          </td>
          <td>
            <template v-if="item.user_code === u_code">
              <m-action
                @action="print(item.code)"
                icon="print"
                color="success"
                tool="Imprimir"
              />
              <m-action @action="edit(item, 'attention')" />
              <m-action
                @action="delA(item)"
                icon="trash"
                color="danger"
                tool="Eliminar"
              />
            </template>
            <i v-else class="icon ion-md-lock icon-md text-muted"></i>
          </td>
        </tr>
      </template>
      <div slot="foot" class="d-flex justify-content-center">
        <pagination :data="pagination" @pagination-change-page="fetchData" />
      </div>
    </m-table>

    <p slot="foot" class="text-center text-primary">
      Registraste {{ attentions.length }} atenciones este mes
    </p>
  </card>
</template>
<script>
import api from "../../Api/attention";
import Month from "../../Components/Views/Month.vue";
import ptypes from "../../Data/personTypes.json";
import { inat } from "../../Mixins/utils";
export default {
  name: "Attention",
  components: { Month },
  mixins: [inat],
  data() {
    return {
      attentions: [],
      pagination: {},
      ptypes,
      columns: [
        "N° de At.",
        "Usuario",
        "Fecha",
        "Modalidad",
        "Título",
        "Tipo",
        "Nombre y apellidos",
        "Acciones"
      ],
      buscado: ""
    };
  },
  computed: {
    filtered() {
      const tester = new RegExp(this.buscado, "i");
      return this.attentions.filter((item) => {
        return tester.test([item.title, item.person.name].join());
      });
    }
  },
  methods: {
    async fetchData(page = 1) {
      const month = this.$store.state.month;
      const { data } = await api.fetchByMonth(month, page);
      const all = data.values;
      this.attentions = [...all.data];
      delete all.data;
      this.pagination = all;
    },
    async print(code) {
      const { data } = await api.print(code);
      this.$downl(data, `Ficha de Atencion N° ${code}`);
    },
    delA(item) {
      this.$snack.show({
        text: this.$confirm("delete", "esta Atención"),
        button: "CONFIRMAR",
        action: async () => {
          const { data } = await api.del(item.code);
          this.attentions.splice(this.attentions.indexOf(item), 1);
          this.$snack.success(data.message);
        }
      });
    }
  }
};
</script>
