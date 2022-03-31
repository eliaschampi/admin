<template>
  <section id="invoice">
    <div class="row justify-content-end">
      <panel class="col-md-8 mb-4" v-if="cachedRegister">
        <alert :dismisable="false">
          <i class="icon ion-md-megaphone" aria-hidden="true"></i>
          Hay una matrícula en proceso del estudiante
          <b class="alert-link">{{ cachedRegister.student_name }}</b>
          Por lo tanto, este comprobante será registrado con esta razón social.
          <b @click="cancel" class="alert-link pointer">
            Para cancelar este proceso clic aquí
          </b>
        </alert>
      </panel>
      <div class="col-md-8" v-else>
        <panel>
          <div class="selectgroup selectgroup-pills mb-2">
            <label class="selectgroup-item">
              <input
                type="radio"
                name="mode_in"
                value="student"
                class="selectgroup-input"
                v-model="invoice.mod"
              />
              <span class="selectgroup-button">Estudiante</span>
            </label>
            <label class="selectgroup-item">
              <input
                type="radio"
                name="mode_in"
                value="customer"
                class="selectgroup-input"
                v-model="invoice.mod"
              />
              <span class="selectgroup-button">Cliente</span>
            </label>
          </div>
          <div
            class="d-flex align-items-center flex-wrap"
            v-if="invoice.mod === 'student'"
          >
            <input-finder
              class="col-md-6"
              who="student"
              :only_current_reg="false"
              :only_current_branch="false"
              :fullname="student.fullname"
            />
            <div class="form-group col-md-6" v-if="student.dni">
              <label for="mats">Matriculas del estudiante</label>
              <select
                class="form-control"
                id="mats"
                v-model="invoice.customer_identifier"
              >
                <option
                  :key="item.code"
                  v-for="item in registers"
                  :value="item.code"
                >
                  {{ registerFullName(item) }}
                </option>
              </select>
            </div>
          </div>
          <!-- customer -->
          <template v-else>
            <div class="addon-group my-0" style="max-width: 25rem">
              <div class="input-group">
                <input
                  class="form-control"
                  placeholder="Ingrese una razón social"
                  type="text"
                  v-model="customer.name"
                />
                <div class="input-group-append">
                  <m-button
                    data-toggle="modal"
                    data-target="#customerModal"
                    size="btn-sm"
                  >
                    Frecuentes
                  </m-button>
                </div>
              </div>
            </div>
            <span
              class="text-primary pointer"
              @click="onClearCustomer"
              v-show="$defined(customer.code)"
            >
              Quitar Cliente frecuente
            </span>
          </template>
        </panel>
      </div>
      <div class="col-md-4 mt-4 mt-md-0 mb-4">
        <panel class="text-center">
          <h5>
            <i class="icon ion-ios-wallet"></i> CAJA:
            <router-link :to="{ name: 'cash' }">
              {{ invoice.cash_code }}
              <span v-show="invoice.cash_code === ''"> ABRIR AHORA </span>
            </router-link>
          </h5>
          <hr />
          <div class="form-group">
            <select
              class="form-control form-control-lg"
              id="voucher_type_select"
              name="voucher_type_select"
              v-model="invoice.type"
            >
              <option value="00">Nota de Venta</option>
              <option value="03">Boleta de Venta</option>
            </select>
          </div>
          <div class="text-muted">
            <h3>N°: {{ invoice.series + " " + invoice.correlative }}</h3>
          </div>
        </panel>
      </div>
    </div>
    <panel :f="true">
      <div class="row p-3">
        <div class="col-lg-9">
          <m-button
            color="btn-inverse-primary btn-icon"
            icon="icon ion-md-add"
            @pum="showDetailModal"
          >
          </m-button>
          <m-button
            color="btn-light"
            @pum="cancel"
            icon="icon ion-md-refresh"
            :disabled="!invoice.details.length"
          >
            Nuevo
          </m-button>
          <m-table :columns="columns" :data="invoice.details" :head="false">
            <template slot="data">
              <tr :key="item.id" v-for="item in invoice.details">
                <td>{{ item.id }}</td>
                <td>{{ item.actiontype.name }}</td>
                <td>{{ item.title }}</td>
                <td>{{ item.topay | currency }}</td>
                <td>{{ item.discount | currency }}</td>
                <td>{{ item.paid | currency }}</td>
                <td>{{ item.pending | currency }}</td>
                <td>
                  <span @click="delItem(item)" class="text-accent pointer">
                    <i class="icon ion-md-remove-circle icon-md"></i>
                  </span>
                </td>
              </tr>
            </template>
          </m-table>
          <alert
            type="alert-danger"
            v-show="errorMessage.length"
            :dismisable="false"
          >
            <div v-for="(item, index) in errorMessage" :key="index">
              <i class="icon ion-md-info"></i> {{ item }}
            </div>
          </alert>
        </div>
        <div class="col-md-12 col-lg-3">
          <div class="p-3 rounded-md shadow-sm bg-light">
            <div class="d-flex justify-content-between font-weight-bold">
              <div class="text-accent">
                <div>Descuento:</div>
                <div>Importe Total:</div>
                <p>Saldo Pendiente:</p>
              </div>
              <div>
                <div>{{ discount | currency }}</div>
                <div>{{ paid | currency }}</div>
                <div>{{ pending | currency }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div slot="foot" class="text-center">
        <m-button icon="icon ion-md-cart" color="btn-primary" @pum="save">
          Finalizar
        </m-button>
      </div>
    </panel>
    <customer @selected="customerSelected" />
    <detail :is-new="true" @before="beforeAddDetail" />
  </section>
</template>

<script>
import api from "../../Api/income";
import regApi from "../../Api/register";
import Customer from "../Customer/Modal";
import cache from "../../Helpers/cache";
import InputFinder from "../../Components/Finder/InputFinder.vue";
import Detail from "./Detail";
import { ticket } from "../../Helpers/ticket";
import { EventBus } from "../../Helpers/bus";
import { hasDetailModal } from "../../Mixins/utils";
export default {
  name: "InvoiceComponent",
  mixins: [hasDetailModal],
  components: { Customer, InputFinder, Detail },
  data() {
    return {
      load: false,
      student: {
        fullname: "Buscar estudiante"
      },
      cachedRegister: null,
      customers: [],
      registers: [],
      invoice: {
        customer_identifier: "",
        section_code: "",
        cash_code: "",
        mod: "student",
        type: "00",
        series: "",
        correlative: "",
        details: []
      },
      errorMessage: [],
      customer: {
        name: ""
      },
      columns: [
        "#",
        "Modalidad de Ingreso",
        "Concepto",
        "Monto a pagar",
        "Descuento",
        "Importe",
        "Saldo",
        "#"
      ]
    };
  },
  mounted() {
    // obtener invoicenumber
    this.fetchInvoiceNumber();

    this.$store.dispatch("getCash").then((r) => {
      if (r && r.state) {
        this.invoice.cash_code = r.code;
      }
    });

    // obtener del cache
    api.showIncomeDetailFromCache().then(({ data }) => {
      this.invoice.details = data.values;
      // detalle guardado
      // si hay matricula en cache, obtener
      if (data.register !== false) {
        this.cachedRegister = data.register;
        this.invoice.mod = "student";
        this.invoice.section_code = data.register.section_code;
      } else {
        const dni = this.$store.getters["student/dni"];
        if (dni) {
          const reg = this.$store.state.register.register;
          if (dni === reg.student_dni) {
            this.student = {
              dni,
              fullname: this.$store.getters["student/fullname"]
            };
            regApi.fetchRegisters(dni).then(({ data }) => {
              this.registers = data.values;
              this.invoice.customer_identifier = reg.code;
            });
          }
        }
      }
      // obtener cash
    });

    EventBus.$on("afterSelectPerson", this.onRegisterSelected);
  },
  computed: {
    discount() {
      return this.invoice.details.reduce((total, item) => {
        return total + parseFloat(item.discount);
      }, 0);
    },
    paid() {
      return this.invoice.details.reduce((total, item) => {
        return total + parseFloat(item.paid);
      }, 0);
    },
    pending() {
      return (
        this.invoice.details.reduce((total, item) => {
          return total + parseFloat(item.topay);
        }, 0) -
        this.discount -
        this.paid
      );
    }
  },
  watch: {
    "invoice.type"() {
      this.fetchInvoiceNumber();
    }
  },
  methods: {
    registerFullName({ year, section_code, level }) {
      return `${year} - ${section_code.substr(-2)} de ${level}`;
    },
    onRegisterSelected({ dni, name, lastname }) {
      this.student = {
        dni,
        fullname: `${name} ${lastname}`
      };
      regApi.fetchRegisters(dni).then(({ data }) => {
        this.registers = data.values;
      });
      $("#finderModal").modal("hide");
    },
    validation() {
      this.errorMessage = [];
      if (this.invoice.details.length === 0) {
        this.errorMessage.push("Falta al menos un registro de ingreso");
        return false;
      }
      if (this.invoice.mod === "customer") {
        if (this.customer.name === "") {
          this.errorMessage.push("Falta elegir un cliente");
          return false;
        }
      } else if (!this.invoice.customer_identifier && !this.cachedRegister) {
        this.errorMessage.push("Falta elegir un Estudiante");
        return false;
      }

      if (this.invoice.cash_code === "") {
        this.errorMessage.push("Falta aperturar la caja");
        return false;
      }
      return true;
    },
    save() {
      if (!this.validation()) return;
      // totales
      this.invoice.total_discount = this.discount;
      this.invoice.total = this.paid;
      const client = { name: "" };
      // identifier

      if (this.invoice.mod === "customer") {
        this.invoice.customer_identifier = this.customer;
        client.name = this.customer.name;
      } else {
        client.name = !this.cachedRegister
          ? this.student.fullname
          : this.cachedRegister.student_name;
      }

      this.$snack.show({
        text: "El comprobante será registrado",
        button: "confirmar",
        action: () => {
          this.load = true;
          api
            .store(this.invoice)
            .then((r) => {
              const data = r.data.income;
              data.user = {
                name: this.$store.state.user.user.name
              };
              data.name = client;
              ticket(data, this.invoice.details);
              this.$snack.success(r.data.message);
              this.cleanAfter();
              this.$router.push("/caja");
            })
            .catch(() => {
              this.errorMessage.push(
                "Ocurrio un error, comunicate con el soporte"
              );
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    },
    beforeAddDetail(newVaues) {
      this.invoice.details = newVaues.values;
      this.$snack.success(newVaues.message);
    },
    delItem(item) {
      api.removeIncomeDetailFromTheCache(item.id).then((r) => {
        this.invoice.details.splice(this.invoice.details.indexOf(item), 1);
        this.$snack.success(r.data);
      });
    },
    customerSelected(val) {
      this.customer = val;
      $("#customerModal").modal("hide");
    },
    onClearCustomer() {
      this.customer = {
        name: ""
      };
    },
    fetchInvoiceNumber() {
      this.$store
        .dispatch("fetchInvoiceNumber", this.invoice.type)
        .then((r) => {
          this.invoice.series = r.serie !== null ? r.serie : "";
          this.invoice.correlative = r.correlative;
        });
    },
    cleanAfter() {
      cache.removeItem(`in_${this.invoice.type}`);
    },
    cancel() {
      this.$snack.show({
        text: "¿Esta seguro de limpiar todos los registros?",
        button: "CONFIRMAR",
        action: () => {
          api.removeAllFromCache().then((r) => {
            this.$snack.success(r.data);
            this.invoice.details = [];
            this.cachedRegister = null;
          });
        }
      });
    }
  },
  beforeDestroy() {
    EventBus.$on("afterSelectPerson", this.onRegisterSelected);
  }
};
</script>
