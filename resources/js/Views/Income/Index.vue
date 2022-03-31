<template>
  <section id="mainIncome">
    <card title="Ingresos">
      <template slot="rb">
        <m-button
          color="btn-inverse-accent btn-icon"
          icon="icon ion-md-download"
          :disabled="!incomes.length"
          @pum="excel"
        />
        <m-router
          :to="{ name: 'invoice' }"
          color="btn-accent btn-icon"
          icon="icon ion-md-add"
          v-can="'AS'"
        />
      </template>
      <div class="row">
        <m-table
          :columns="columns"
          :data="incomes"
          :fetch="fetch"
          v-model="buscado"
          class="col-md-12"
        >
          <range @fetch="fetch" />
          <template slot="data">
            <tr :key="item.code" v-for="item in filtered">
              <td>{{ item.user.name }}</td>
              <td>
                <span class="text-primary">
                  {{ item.created_at | datetim }}
                </span>
              </td>
              <td>{{ types[item.type] }}</td>
              <td>{{ item.serie }}</td>
              <td>
                <template v-if="item.name.type === 'c'"> Cliente </template>
                <template v-else>
                  Estudiante
                  <Span :data-tooltip="item.name.type | full">
                    <i
                      class="icon ion-md-information-circle icon-sm text-primary cursor-pointer"
                    ></i>
                  </Span>
                </template>
              </td>
              <td>
                <b>{{ item.name.name }}</b>
              </td>
              <td>{{ item.total | currency }}</td>
              <td>
                <m-action
                  @action="showDetail(item)"
                  icon="eye"
                  tool="Ver Detalle"
                />
                <m-action
                  @action="print(item)"
                  icon="print"
                  color="success"
                  tool="Ticket"
                />
                <m-action
                  v-show="isToday(item.created_at)"
                  @action="showCM(item)"
                  icon="trash"
                  color="danger"
                  tool="Anular"
                />
              </td>
            </tr>
          </template>
          <h5 class="text-center my-3" slot="foot">
            Total de Ingresos: {{ total | currency }}
          </h5>
        </m-table>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Total de Ingresos: {{ incomes.length }}
      </p>
    </card>
    <modal
      id="details"
      size="modal-lg"
      btn-name="cerrar"
      @sub="closed"
      :title="'Detalles del Ingreso: ' + selected.serie"
    >
      <m-table :columns="dColumns" :data="details" :head="false">
        <template slot="data">
          <tr v-for="(item, index) in details" :key="index">
            <td>{{ item.actiontype.name }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.topay | currency }}</td>
            <td>{{ item.discount | currency }}</td>
            <td>{{ item.paid | currency }}</td>
            <td>
              {{ (item.topay - item.discount - item.paid) | currency }}
            </td>
          </tr>
        </template>
      </m-table>
    </modal>
    <cancel :serie="selected.serie" @save="cancel" />
  </section>
</template>
<script>
import api from "../../Api/income";
import { incomeMod } from "../../Mixins/utils";
import Range from "../../Components/Ui/Range";
import { ticket } from "../../Helpers/ticket";
import { mapGetters } from "vuex";
import Cancel from "./Cancel";
export default {
  mixins: [incomeMod],
  components: { Range, Cancel },
  data() {
    return {
      incomes: [],
      details: [],
      selected: {},
      buscado: "",
      columns: [
        "Usuario",
        "Fecha",
        "Comprobante",
        "Correlativo",
        "Tipo",
        "Cliente",
        "Total",
        "Acciones"
      ],
      dColumns: [
        "Modalidad",
        "Concepto",
        "Monto a Pagar",
        "Descuento",
        "Importe",
        "Saldo"
      ]
    };
  },
  computed: {
    ...mapGetters(["range", "isToday"]),
    filtered() {
      const rex = RegExp(this.buscado, "i");
      return this.incomes.filter((item) => {
        return rex.test(item.name.name);
      });
    },
    total() {
      return this.filtered.reduce((acu, item) => {
        return acu + parseFloat(item.total);
      }, 0);
    }
  },
  methods: {
    closed() {
      this.selected = {};
      $("#details").modal("hide");
    },
    showCM(item) {
      this.selected = item;
      $("#cancel").modal("show");
    },
    fetch() {
      return api
        .fetchByDate({
          ...this.range,
          plucked: 0
        })
        .then((r) => {
          this.incomes = r.data.values;
        });
    },
    showDetail(item) {
      api.fetchDetailByIncome(item.code).then((r) => {
        this.selected = item;
        this.details = r.data.values;
        $("#details").modal("show");
      });
    },
    cancel(justification) {
      const payload = {
        code: this.selected.code,
        justification
      };
      if (this.selected.has_register !== null) {
        payload.section_code = this.selected.has_register.register.section_code;
      } else {
        payload.section_code = "";
      }
      api.canceled(payload).then((r) => {
        this.incomes.splice(this.incomes.indexOf(this.selected), 1);
        this.selected = {};
        $("#cancel").modal("hide");
        this.$snack.success(r.data);
      });
    },
    print(item) {
      api.fetchDetailByIncome(item.code).then((r) => {
        ticket(item, r.data.values).then(() => {
          this.$snack.success("el ticket se ha enviado a la impresora");
        });
      });
    },
    excel() {
      api.exportToExcel(this.range).then((r) => {
        const name = `Ingresos Desde ${this.range.from} Hasta ${this.range.to}`;
        this.$downl(r.data, name, ".xlsx");
      });
    }
  }
};
</script>
