<template>
  <card title="Registro de atenciones">
    <template slot="rb">
      <m-router :to="{ name: 'new_attention' }" color="btn-inverse-accent">
        Nueva atención
      </m-router>
    </template>
    <div class="row">
      <m-table
        class="col-md-12"
        :columns="columns"
        :data="attentions"
        :fetch="fetchData"
        v-model="buscado"
      >
        <month @monthchange="fetchData" />
        <template slot="data">
          <tr :key="item.code" v-for="item in filtered">
            <td>{{ item.code }}</td>
            <td>{{ item.user.name }}</td>
            <td>{{ item.created_at | datetim }}</td>
            <td>
              {{ item.type === "p" ? "Presencial" : "Virtual" }}
            </td>
            <td style="max-width: 10rem">{{ item.title }}</td>
            <td>{{ ptypes[item.person_type] }}</td>
            <td class="font-weight-medium text-primary">
              <i class="icon ion-md-people icon-sm text-accent"></i>
              {{ `${item.person.name} ${item.person.lastname}` }}
            </td>
            <td>
              <template v-if="item.user_code === $store.state.user.user.code">
                <m-action @action="edit(item)" />
                <m-action
                  @action="delA(item)"
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
      Registraste {{ attentions.length }} atenciones este mes
    </p>
  </card>
</template>
<script>
import api from "../../Api/attention";
import Month from "../../Components/Views/Month.vue";
import cache from "../../Helpers/cache";
export default {
  name: "Attention",
  components: { Month },
  data() {
    return {
      attentions: [],
      pagination: {},
      ptypes: {
        student: "Estudiante",
        teacher: "Docente",
        family: "Apoderado"
      },
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
    edit(item) {
      cache.setItem("attention", item);
      this.$router.push({ name: "new_attention", params: { code: item.code } });
    },
    async fetchData(page = 1) {
      const month = this.$store.state.month;
      const { data } = await api.fetchByMonth(month, page);
      const all = data.values;
      this.attentions = [...all.data];
      delete all.data;
      this.pagination = all;
    },
    print(code) {
      api.print(code).then((r) => {
        this.$downl(r.data, `Ficha de Atencion N° ${code}`);
      });
    },
    delA(item) {
      this.$snack.show({
        text: this.$confirm("delete", "esta Atención"),
        button: "CONFIRMAR",
        action: () => {
          api.del(item.code).then((r) => {
            this.attentions.splice(this.attentions.indexOf(item), 1);
            this.$snack.success(r.data.message);
          });
        }
      });
    }
  }
};
</script>
