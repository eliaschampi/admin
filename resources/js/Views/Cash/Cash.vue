<template>
  <section id="cash">
    <panel :title="'Caja de ' + title">
      <div class="d-flex">
        <datepick @fetch="getCash" />
        <span class="mt-4 ml-4 pointer" v-if="isToday" @click="openReAuth">
          <i :class="cash_icon"></i>
        </span>
      </div>
    </panel>
    <div class="row mt-4" v-if="!cash_closed">
      <div class="col-md-6">
        <card title="Saldo">
          <div class="d-flex flex-column">
            <h1 class="font-weight-light">
              <i class="icon ion-ios-wallet text-success"></i>
              {{ cash.cash | currency }}
            </h1>

            <p class="font-weight-light my-3">
              <i>Actualizado por última vez: {{ cash.updated_at | ago }}</i>
            </p>
            <p class="font-weight-medium">
              <span class="text-accent">Aperturado por:</span>
              {{ cash.user ? cash.user.name : "" }}
            </p>
            <p class="font-weight-medium">
              <span class="text-accent">Saldo Inicial:</span>
              {{ initial | currency }}
            </p>
            <p class="font-weight-medium">
              <span class="text-accent">Total Acumulado:</span>
              {{ (initial + Number(cash.isum)) | currency }}
            </p>
            <p class="font-weight-medium">
              <span class="text-accent">Total Egreso:</span>
              {{ expense | currency }}
            </p>
            <hr />
            <template v-if="hasSur">
              <p class="font-weight-medium">
                <span class="text-accent"> Monto Rendido: </span>
                {{ cash.surrendered.amount | currency }}
              </p>
              <p class="font-weight-medium">
                <span class="text-accent">Razon:</span>
                {{ cash.surrendered.recipient }}
              </p>
            </template>
            <p v-else class="text-center text-muted">No ha sido rendido</p>
          </div>
          <template slot="foot">
            <m-button
              :disabled="hasSur || !isToday"
              size="btn-sm"
              color="btn-primary"
              data-toggle="modal"
              data-target="#surrenderM"
              >Rendir
            </m-button>
            <m-router color="btn-light" size="btn-sm" :to="{ name: 'cashes' }"
              >Reporte Mensual
            </m-router>
          </template>
        </card>
      </div>
      <div class="col-md-6 mt-5 mt-md-0">
        <div class="d-flex flex-column">
          <widget
            title="Ingresos"
            icon="icon ion-ios-trending-up text-success"
            :content="cash.isum"
            gooAdd="invoice"
            @golist="handleGoList('incomes')"
            where="incomes"
            :is-today="isToday"
            :count="cash.incomes_count"
          />
          <widget
            class="mt-4"
            title="Gastos"
            icon="icon ion-ios-trending-down text-danger"
            :content="cash.esum"
            gooAdd="new_expense"
            @golist="handleGoList('expense')"
            :is-today="isToday"
            :count="cash.expenses_count"
          />
        </div>
      </div>
      <modal
        id="surrenderM"
        :title="'Rendir Caja: ' + cash.cash"
        @sub="surrenderCash"
      >
        <m-input
          id="Monto"
          type="number"
          step="0.01"
          v-model="surrender.amount"
          v-validate="'required|min_value:1|max_value:' + cash.cash"
          :error="errors.first('Monto')"
        />
        <m-input
          id="Recibe"
          step="0.01"
          label="A quien se le entrega"
          v-model="surrender.recipient"
          v-validate="'required'"
          :error="errors.first('Recibe')"
        />
      </modal>
    </div>
    <cash :lastCash="lastCash" @save="open" />
  </section>
</template>
<script>
import cash from "./NewCash";
import api from "../../Api/cash";
import Datepick from "../../Components/Views/Datepick";
import Widget from "./Widget";
import { iso } from "../../Helpers/date";
import { EventBus } from "../../Helpers/bus";
export default {
  name: "IndexCash",
  components: { cash, Datepick, Widget },
  data() {
    return {
      cash: null,
      lastCash: "0.00",
      surrender: {
        amount: "0.00"
      },
      isToday: true
    };
  },
  created() {
    this.getCash();
  },
  mounted() {
    EventBus.$on("reauthed", this.manageCash);
  },
  computed: {
    cash_closed() {
      if (this.cash === null) {
        return true;
      }
      return this.isToday ? !this.cash.state : false;
    },
    cash_icon() {
      if (this.cash === null) {
        return "icon ion-md-log-in icon-lg text-accent";
      }
      if (this.cash.state) {
        return "icon ion-md-unlock icon-lg text-success";
      }
      return "icon ion-md-lock icon-lg text-accent";
    },
    hasSur() {
      if (this.cash.code) {
        return this.cash.surrendered !== null;
      }
      return false;
    },
    initial() {
      let sum = Number(this.cash.cash) + Number(this.cash.esum);
      if (this.hasSur) {
        sum += Number(this.cash.surrendered.amount);
      }
      return sum - this.cash.isum;
    },
    expense() {
      if (this.hasSur) {
        return Number(this.cash.surrendered.amount) + Number(this.cash.esum);
      }
      return this.cash.esum;
    },
    mytoday() {
      return this.$store.state.date;
    },
    title() {
      return this.isToday ? "hoy" : this.$options.filters.month(this.mytoday);
    }
  },
  methods: {
    handleGoList(where = "incomes") {
      const date = new Date(this.mytoday.split("-"));
      date.setDate(date.getDate() + 1);
      this.$store.commit("SET_RANGE", {
        from: this.mytoday,
        to: iso(date)
      });
      this.$router.push({ name: where });
    },
    getCash() {
      this.$store
        .dispatch("getCash", this.mytoday)
        .then((r) => {
          this.cash = r;
          if (r === null && !this.isToday) {
            this.$snack.show("No ha sido aperturado");
          }
        })
        .finally(() => {
          this.isToday = this.mytoday === iso();
        });
    },
    manageCash() {
      if (this.cash !== null) {
        api.toggleCash(this.cash.code).then(() => {
          this.getCash();
        });
      } else {
        api.fetchLastCash().then((r) => {
          this.lastCash = r.data.cash;
          $("#new_cash").modal("show");
        });
      }
    },
    open() {
      api
        .open({
          cash: this.lastCash
        })
        .then((r) => {
          this.$snack.success(r.data);
          $("#new_cash").modal("hide");
          this.getCash();
        });
    },
    surrenderCash() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          api.surrender(this.surrender, this.cash.code).then((r) => {
            this.$snack.success(r.data);
            this.getCash();
            $("#surrenderM").modal("hide");
          });
        }
      });
    },
    openReAuth() {
      let title = "Aperturar caja";
      if (this.cash !== null) {
        if (this.cash.state) title = "Asegurar o finalizar caja";
        else title = "Habilitar caja";
      }
      EventBus.$emit("checkauth", title, "/check");
    }
  },
  beforeDestroy() {
    EventBus.$off("reauthed", this.manageCash);
  }
};
</script>
