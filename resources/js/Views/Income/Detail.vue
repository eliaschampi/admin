<template>
  <modal
    id="addDetail"
    @sub="save"
    btn-name="Agregar"
    title="Agregar un Ingreso"
  >
    <template v-if="isNew">
      <m-switch
        class="col-md-12"
        id="mycheck"
        text="Cancelando Saldo Anterior"
        v-model="canceledM"
      />
      <div class="form-row mt-4">
        <div class="form-group col-md-6">
          <label for="catid">Modalidad</label>
          <select
            class="form-control"
            id="catid"
            name="modalidad"
            v-validate="'required'"
            v-model="actiontype"
          >
            <option :key="index" v-for="(item, index) in types" :value="item">
              {{ item.name }}
            </option>
          </select>
          <span class="small text-danger" v-show="errors.has('modalidad')">
            {{ errors.first("modalidad") }}
          </span>
        </div>

        <div
          class="form-group col-md-6"
          v-if="actiontype.name === 'Mensualidad'"
        >
          <label for="mesid">Mes</label>
          <select
            class="form-control"
            id="mesid"
            name="mes"
            v-validate="'required'"
            v-model="month"
          >
            <option
              :key="index"
              v-for="(item, index) in months"
              :value="item.month"
            >
              {{ item.month }}
            </option>
          </select>
          <span class="small text-danger" v-show="errors.has('mes')">
            {{ errors.first("mes") }}
          </span>
        </div>
        <m-input
          v-else
          class="col-md-6"
          id="Concepto"
          label="Concepto"
          maxlength="50"
          placeholder="opcional"
          v-model="title"
        />
      </div>
    </template>
    <div class="row">
      <m-input
        class="col-md-6 font-weight-bold"
        id="Pagar"
        label="Monto a pagar:"
        v-model="topay"
        @blur="handleToPayBlur"
        type="number"
        step="0.01"
        v-validate="'required|min_value:0'"
        :error="errors.first('Pagar')"
      />
      <m-input
        class="col-md-6 font-weight-bold"
        id="Importe"
        v-model="paid"
        type="number"
        step="0.01"
        v-validate="valiPaid"
        :error="errors.first('Importe')"
      />
    </div>
    <div class="row">
      <m-input
        class="col-md-6 font-weight-bold"
        id="Descuento"
        v-model.lazy="discount"
        @blur="handleDiscountBlur"
        type="number"
        step="0.01"
        v-validate="'required|min_value:0|max_value:' + topay"
        :error="errors.first('Descuento')"
      />
      <div class="col-md-6 font-weight-bold">
        <p class="text-accent">{{ newLabel }}</p>
        <p>Saldo: {{ pending | currency }}</p>
      </div>
    </div>
  </modal>
</template>
<script>
import months from "../../Data/months.json";
import api from "../../Api/income";
export default {
  props: {
    canceled: Boolean,
    isNew: Boolean
  },
  data() {
    return {
      months,
      canceledM: this.canceled,
      discount: "0.00",
      title: ""
    };
  },
  watch: {
    canceled(val) {
      this.canceledM = val;
    }
  },
  computed: {
    types() {
      return this.$store.state.cats;
    },
    valiPaid() {
      const paid = this.canceledM ? this.topay : "0.00";
      return { required: true, min_value: paid, max_value: this.topay };
    },
    pending() {
      return this.topay - this.discount - this.paid;
    },
    newLabel() {
      let title = this.title;
      if (this.actiontype.name === "Mensualidad") {
        title = this.month;
      }
      if (this.canceledM) {
        return `${title} Cancelado`;
      } else if (this.pending > 0) {
        return `${title} Saldo Pendiente`;
      }
      return title;
    },
    actiontype: {
      get() {
        return this.$store.state.detail.actiontype;
      },
      set(value) {
        this.updateDetailByField("actiontype", value);
      }
    },
    month: {
      get() {
        return this.$store.state.detail.month;
      },
      set(value) {
        this.updateDetailByField("month", value);
      }
    },
    topay: {
      get() {
        return this.$store.state.detail.topay;
      },
      set(value) {
        this.updateDetailByField("topay", value);
      }
    },
    paid: {
      get() {
        return this.$store.state.detail.paid;
      },
      set(value) {
        this.updateDetailByField("paid", value);
      }
    }
  },
  methods: {
    updateDetailByField(field, value) {
      this.$store.commit("UPDATE_DETAIL_BY_FIELD", {
        field,
        value
      });
    },
    handleToPayBlur() {
      let paid = this.topay;
      if (Number(this.discount) > 0) {
        paid = paid - Number(this.discount);
      }
      this.$store.commit("UPDATE_DETAIL", {
        paid
      });
    },
    handleDiscountBlur() {
      this.$store.commit("UPDATE_DETAIL", {
        paid: this.topay - this.discount
      });
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          if (this.pending < 0) {
            this.$snack.warning("Su saldo pendiente no puede ser negativo");
            return;
          }
          this.$store.commit("UPDATE_DETAIL", {
            title: this.newLabel,
            pending: this.pending,
            discount: this.discount
          });

          api.storeIncomeDetailInCache(this.$store.state.detail).then((r) => {
            $("#addDetail").modal("hide");
            if (["invoice", "register"].includes(this.$route.name)) {
              this.$emit("before", r.data);
            } else {
              setTimeout(() => {
                this.$router.push({ name: "invoice" });
              }, 100);
            }
          });
        }
      });
    }
  }
};
</script>
