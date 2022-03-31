<template>
  <section id="Egresos">
    <card title="Registro de Gastos">
      <template slot="rb">
        <m-button
          @pum="excel"
          color="btn-inverse-accent btn-icon"
          icon="icon ion-md-download"
          :disabled="!expenses.length"
        />
        <m-router
          :to="{ name: 'new_expense' }"
          color="btn-accent btn-icon"
          icon="icon ion-md-add"
          v-can="'AS'"
        />
      </template>
      <div class="row">
        <div class="col-md-12">
          <m-table
            :columns="columns"
            :data="expenses"
            :fetch="fetch"
            v-model="buscado"
          >
            <range @fetch="fetch" />
            <template slot="data">
              <tr v-for="(item, index) in filtered" :key="index">
                <td>{{ item.code }}</td>
                <td>{{ item.created_at | datetim }}</td>
                <td>{{ item.actiontype.name }}</td>
                <td>
                  <div style="max-width: 13rem">
                    {{ item.description }}
                  </div>
                </td>
                <td>{{ item.total | currency }}</td>
                <td>
                  <m-action
                    @action="
                      $router.push({
                        name: 'update_expense',
                        params: { code: item.code }
                      })
                    "
                    icon="eye"
                    tool="Detalle"
                    v-can="'AS'"
                  />
                  <m-action
                    @action="print(item.code)"
                    icon="print"
                    color="success"
                    tool="Imprimir"
                  />
                  <m-action
                    v-show="isToday(item.created_at)"
                    @action="delE(item)"
                    icon="trash"
                    color="danger"
                    tool="Eliminar"
                  />
                </td>
              </tr>
            </template>
            <h5 class="text-center my-3" slot="foot">
              Total de Gastos: {{ total | currency }}
            </h5>
          </m-table>
        </div>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Total de gastos: {{ expenses.length }}
      </p>
    </card>
  </section>
</template>
<script>
import { mapGetters } from "vuex";
import Range from "../../Components/Ui/Range";
import api from "../../Api/expense";
export default {
  components: {
    Range
  },
  data() {
    return {
      columns: [
        "Código",
        "Fecha",
        "Tipo de Gasto",
        "Concepto",
        "Monto",
        "Acciones"
      ],
      expenses: [],
      buscado: ""
    };
  },
  computed: {
    ...mapGetters(["range", "isToday"]),
    filtered() {
      const rex = new RegExp(this.buscado, "i");
      return this.expenses.filter((item) => {
        return rex.test([item.description, item.actiontype.name].join());
      });
    },
    total() {
      return this.filtered.reduce((acu, item) => {
        return acu + parseFloat(item.total);
      }, 0);
    }
  },
  methods: {
    fetch() {
      return api
        .fetchByDates({
          ...this.range,
          plucked: 0
        })
        .then((r) => {
          this.expenses = r.data.values;
        });
    },
    excel() {
      api.exportToExcel(this.range).then((r) => {
        const name = `Gastos Desde ${this.range.from} Hasta ${this.range.to}`;
        this.$downl(r.data, name, ".xlsx");
      });
    },
    delE(item) {
      this.$snack.show({
        text: "Este registro será eliminado",
        button: "Confirmar",
        action: () => {
          api.del(item.code).then(() => {
            this.expenses.splice(this.expenses.indexOf(item), 1);
          });
        }
      });
    },
    print(code) {
      api.print(code).then((r) => {
        this.$downl(r.data, `Reporte de Egreso Nro ${code}`);
      });
    }
  }
};
</script>
