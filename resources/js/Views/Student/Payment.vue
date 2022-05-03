<template>
  <div id="payments">
    <card title="Pagos del Estudiante">
      <m-button
        slot="rb"
        @pum="print"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-ios-cloud-download icon-md"
      />
      <div class="addon-group mb-3">
        <div class="input-group" style="max-width: 12rem">
          <input type="number" v-model="year" class="form-control" />
          <div class="input-group-append">
            <m-button
              @pum="fetchData(year)"
              color="btn-primary"
              icon="icon ion-md-search"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <m-table
          :columns="columns"
          :data="payments"
          :head="false"
          class="col-md-12"
          emptytext="El estudiante aun no ha realizado ningun pago"
        >
          <template slot="data">
            <tr
              :key="item.code"
              v-for="item in payments"
              :class="incompleted(item) ? 'incompleted' : 'completed'"
            >
              <td>{{ types[item.income.type] }}</td>
              <td>{{ item.income.serie }}</td>
              <td>{{ item.actiontype.name }}</td>
              <td>{{ item.title }}</td>
              <td>{{ item.topay | currency }}</td>
              <td>{{ item.discount | currency }}</td>
              <td>{{ item.paid | currency }}</td>
              <td>
                {{ (item.topay - item.discount - item.paid) | currency }}
              </td>
              <td>{{ item.income.created_at | largeDate }}</td>
              <td>
                <span
                  class="text-danger pointer"
                  @click="cancelPay(item)"
                  v-show="incompleted(item)"
                >
                  <i class="icon ion-md-cart icon-md"></i>
                </span>
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <div class="row">
        <div class="col-md-4">
          <panel class="head-panel bg-light">
            <m-button @pum="newPay" :block="true" size="btn-sm" v-can="'S'">
              Registrar un Pago
            </m-button>
            <register
              class="text-center mt-4"
              if-not-has-register="No se encontró matricula."
            />
            <hr />
            <p class="my-2">
              <b>Total Importe:</b>
              <span class="text-primary">{{ paid | currency }}</span>
            </p>
            <p class="my-2">
              <b>Total Descuento:</b>
              <span class="text-primary">{{ discount | currency }}</span>
            </p>
            <p class="my-2">
              <b>Total pendiente por pagar:</b>
              <span class="text-primary">{{ pending | currency }}</span>
            </p>
          </panel>
        </div>
        <div
          class="
            col-md-8
            mt-4 mt-md-0
            d-flex
            flex-row flex-wrap
            align-content-start
          "
        >
          <div
            class="topay bg-light pointer"
            v-for="(item, index) in remaining"
            :key="index"
            @click="monthPay(item)"
          >
            <i class="icon ion-md-cart icon-lg text-primary"></i>
            <p class="mb-2">{{ item }}</p>
          </div>
        </div>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Total de registros: {{ payments.length }}
      </p>
    </card>
    <detail :isNew="isNew" :canceled="canceled" />
  </div>
</template>
<script>
import { incomeMod, hasDetailModal } from "@/Mixins/utils";
import { mapGetters, mapState } from "vuex";
import api from "@/Api/income";
import month from "@/Data/months.json";
import Detail from "@/Views/Income/Detail";
import Register from "@/Components/Views/Register";
import _ from "lodash";
import { fetchOnWatch } from "@/Mixins";
export default {
  mixins: [incomeMod, fetchOnWatch, hasDetailModal],
  components: { Detail, Register },
  data() {
    return {
      columns: [
        "Comprobante",
        "Correlativo",
        "Modalidad",
        "Concepto",
        "Monto a Pagar",
        "Descuento",
        "Total Importe",
        "Saldo",
        "Fecha de pago",
        "#"
      ],
      payments: [],
      load: false,
      year: new Date().getFullYear(),
      canceled: false,
      isNew: false,
      month
    };
  },
  mounted() {
    const dni = this.$route.params.dni;
    if (this.$defined(dni)) {
      this.fetchData();
    }
    $("#addDetail").on("hide.bs.modal", () => {
      this.isNew = false;
      this.canceled = false;
    });
  },
  computed: {
    ...mapGetters("student", ["dni", "fullname"]),
    ...mapState("register", ["register"]),
    pending() {
      return this.monthly * this.remaining.length;
    },
    discount() {
      return this.payments.reduce((total, item) => {
        return total + parseFloat(item.discount);
      }, 0);
    },
    paid() {
      return this.payments.reduce((total, item) => {
        return total + parseFloat(item.paid);
      }, 0);
    },
    monthly() {
      return this.register !== null ? this.register.monthly : "0.00";
    },
    remaining() {
      if (this.register === null) return [];
      const converted = [];
      this.month.forEach((i) => {
        const exist = this.payments.some((item) => {
          if (item.cashactiontype_code === 200) {
            return item.title.includes(i.month);
          }
          return false;
        });
        if (exist === false && !["Enero", "Febrero"].includes(i.month)) {
          converted.push(i.month);
        }
      });
      return converted;
    }
  },
  methods: {
    incompleted(item) {
      return item.topay - item.paid - item.discount > 0;
    },
    fetchData(mod = "last_register") {
      this.load = true;
      this.$store
        .dispatch("register/fetchRegister", {
          dni: this.dni,
          states: "afi",
          mod
        })
        .then((register) => {
          if (register !== null) {
            this.year = register.year;
            api.fetchStudentPayments(register.code).then((r) => {
              this.payments = _.orderBy(r.data.values, "income.created_at");
            });
          } else {
            this.payments = [];
          }
        })
        .finally(() => {
          this.load = false;
        });
    },
    print() {
      if (this.register === null) return;
      const data = {
        register_code: this.register.code,
        name: this.fullname.split(" ").join("_")
      };
      api.printStudentPayments(data).then((r) => {
        this.$downl(r.data, `Pagos del Estudiante ${data.name}`);
      });
    },
    newPay() {
      this.isNew = true;
      this.showDetailModal();
    },
    cancelPay(item) {
      this.canceled = true;
      const balance = item.topay - item.paid - item.discount;
      this.$store.commit("SET_DETAIL", {
        detail: {
          actiontype: item.actiontype,
          topay: balance,
          paid: balance,
          month: item.title
        }
      });
      this.showDetailModal();
    },
    monthPay(month) {
      if (!this.$store.getters["can"]("SA")) {
        this.$snack.show("Actualiza el año académico");
        return;
      }
      this.$store.commit("SET_DETAIL", {
        detail: {
          actiontype: {
            code: "200",
            name: "Mensualidad"
          },
          topay: this.monthly,
          paid: this.monthly,
          month
        }
      });
      this.showDetailModal();
    }
  }
};
</script>
<style scoped>
.incompleted {
  border-left: 6px solid rgba(255, 0, 0, 0.925);
}
.completed {
  border-left: 6px solid rgb(3, 190, 3);
}
.topay {
  width: 120px;
  height: 90px;
  padding: 5px;
  margin: 10px;
  text-align: center;
  box-shadow: rgba(11, 11, 112, 0.05) 0px 4px 16px,
    rgba(12, 12, 131, 0.05) 0px 8px 32px;
  border-radius: 10%;
  font-weight: bold;
}
</style>
