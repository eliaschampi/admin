<template>
  <section id="NewExpense">
    <m-form :title="title" :btn-name="btnName" @save="save">
      <div class="form-row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="gtypesel">Tipo de Gasto</label>
            <select
              class="form-control"
              id="gtypesel"
              name="tipo"
              v-validate="'required'"
              v-model="expense.cashactiontype_code"
            >
              <option :key="item.code" v-for="item in types" :value="item.code">
                {{ item.name }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('tipo')">
              {{ errors.first("tipo") }}
            </span>
          </div>
          <m-textarea
            id="Concepto"
            v-model="expense.description"
            v-validate="'required|min:10|max:150'"
            size="2"
            label="Concepto"
            :error="errors.first('Concepto')"
          />
          <m-input
            id="Vaucher"
            v-model="expense.voucher_num"
            v-validate="'max:20'"
            label="N° de Comprobante"
            :error="errors.first('Vaucher')"
          />
        </div>
        <div class="col-md-6">
          <m-input
            id="Caja"
            readonly
            v-validate="'required'"
            :error="errors.first('Caja')"
            :value="expense.cash_code"
          >
            <i class="icon ion-md-lock prefix text-accent"></i>
          </m-input>

          <m-switch
            class="mb-2"
            id="hfwerf"
            :dis="!isnew"
            text="Registrar detalle de comprobante en lugar de un total"
            v-model="hasDetail"
          />

          <m-input
            v-if="!hasDetail"
            id="Total"
            type="number"
            step="0.01"
            v-validate="'required|max_value:' + cash"
            :error="errors.first('Total')"
            v-model="expense.total"
          >
          </m-input>
          <template v-else>
            <m-button
              data-toggle="modal"
              data-target="#NewDetail"
              size="btn-sm"
            >
              Agregar
            </m-button>
            <hr />
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Descripción</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr :key="index" v-for="(item, index) in expense.detail">
                  <th>{{ item.description }}</th>
                  <th>{{ item.unit_price | currency }}</th>
                  <th>{{ item.quantity }}</th>
                  <th>
                    {{ (item.unit_price * item.quantity) | currency }}
                  </th>
                  <th>
                    <span
                      class="text-accent pointer"
                      v-if="!item.code"
                      @click="remove(item)"
                    >
                      <i class="icon ion-md-remove-circle icon-md"></i>
                    </span>
                  </th>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4">Total: {{ total | currency }}</th>
                </tr>
              </tfoot>
            </table>
          </template>
        </div>
      </div>
    </m-form>
    <detail @save="addD" />
  </section>
</template>
<script>
import api from "../../Api/expense";
import catApi from "../../Api/cat";
import Detail from "./Detail";
export default {
  components: { Detail },
  data() {
    return {
      title: "Registrar un gasto",
      btnName: "Guardar Registro",
      expense: {
        detail: []
      },
      types: [],
      cash: 0,
      hasDetail: false
    };
  },
  created() {
    catApi.fetch("Egreso").then((r) => {
      this.types = r.data.values;
      if (!this.isnew) {
        this.title = "Modificar un Gasto";
        this.btnName = "Guardar Cambios";
        api
          .fetch(this.$route.params.code)
          .then((r) => {
            this.expense = r.data.expense;
            this.hasDetail = this.expense.detail.length > 0;
          })
          .catch((error) => {
            if (error.code === 404) {
              this.$router.push({ name: "not_found" });
            }
          });
      }
    });
    this.$store.dispatch("getCash").then((r) => {
      if (r.state) {
        this.expense.cash_code = r.code;
        this.cash = r.cash;
      }
    });
  },
  computed: {
    isnew() {
      return this.$route.name === "new_expense";
    },
    total() {
      return this.expense.detail.reduce((acu, item) => {
        return acu + item.unit_price * item.quantity; //eslint-disable-line
      }, 0);
    }
  },
  methods: {
    addD(detail) {
      this.expense.detail.push(Object.assign({}, detail));
      $("#NewDetail").modal("hide");
    },
    store(expense) {
      if (this.isnew) {
        return api.store(expense);
      } else {
        return api.update(expense);
      }
    },
    save() {
      this.$validator.validateAll().then((res) => {
        if (res) {
          if (this.hasDetail) {
            if (this.total > this.cash) {
              this.$snack.danger("Monto de Egreso no puede Superar a la Caja");
              return;
            }
            if (!this.expense.detail.length) {
              this.$snack.danger("Falta registrar al menos un detalle");
              return;
            }
            this.expense.total = this.total;
          }
          this.store(this.expense).then(({ data }) => {
            this.$snack.success(data);
            this.$router.push({ name: "expense" });
          });
        }
      });
    },
    remove(item) {
      this.expense.detail.splice(this.expense.detail.indexOf(item), 1);
    }
  }
};
</script>
