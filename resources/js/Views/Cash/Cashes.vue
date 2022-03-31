<template>
  <card title="Resumen de Ingresos y Egresos">
    <m-button
      :disabled="!cashes.length"
      @pum="excel"
      color="btn-accent btn-icon"
      size="btn-sm"
      class="px-2"
      slot="rb"
      icon="icon ion-md-cloud-download icon-md"
    />
    <div class="row">
      <month class="col-md-3" @monthchange="fetch" />
      <m-table
        class="col-md-12"
        :columns="columns"
        :fetch="fetch"
        :data="cashes"
        :head="false"
      >
        <template slot="data">
          <tr v-for="(item, index) in cashes" :key="index">
            <td>
              <b>{{ item.created_at | days }}</b>
            </td>
            <td>{{ item.user ? item.user.name : "" }}</td>
            <td>{{ item.code }}</td>
            <td>{{ initial(item) }}</td>
            <td>
              <div class="sum" @click="gooList('incomes', item.created_at)">
                <i class="bg-success"></i>
                <u>{{ item.isum | currency }}</u>
              </div>
            </td>
            <td>
              <div class="sum" @click="gooList('expense', item.created_at)">
                <i class="bg-accent"></i>
                <u>{{ item.esum | currency }}</u>
              </div>
            </td>
            <td>
              <div v-if="item.surrendered !== null">
                {{ item.surrendered.amount | currency }}
                <span :data-tooltip="item.surrendered.recipient">
                  <i
                    class="icon ion-md-information-circle icon-sm text-primary cursor-pointer"
                  ></i>
                </span>
              </div>
            </td>
            <td>{{ item.cash | currency }}</td>
            <td>
              <m-action
                @action="gooCash(item.created_at)"
                icon="cart"
                tool="Caja"
                v-can="'AS'"
              />
            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td>
              <b class="text-primary">Total: </b>
              {{ incomes | currency }}
            </td>
            <td>
              <b class="text-primary">Total: </b>
              {{ expenses | currency }}
            </td>
            <td>
              <b class="text-primary">Total: </b>
              {{ surrendered | currency }}
            </td>
            <td colspan="2"></td>
          </tr>
        </template>
      </m-table>
    </div>
    <p slot="foot" class="text-center text-primary">
      Hay {{ cashes.length }} cajas aperturados este mes
    </p>
  </card>
</template>
<script>
import api from "../../Api/cash";
import Month from "../../Components/Views/Month.vue";
import { iso } from "../../Helpers/date";
export default {
  name: "Cashes",
  components: { Month },
  data() {
    return {
      columns: [
        "Dia",
        "Aperturado por:",
        "Código",
        "Saldo Inicial",
        "Ingresos",
        "Gastos",
        "Rendido",
        "Saldo",
        "Caja"
      ],
      cashes: [],
      load: false,
      lastCash: 0
    };
  },
  computed: {
    incomes() {
      return this.cashes.reduce((acu, item) => {
        if (item.isum !== null) {
          return acu + parseFloat(item.isum);
        }
        return acu + 0;
      }, 0);
    },
    expenses() {
      return this.cashes.reduce((acu, item) => {
        if (item.esum !== null) {
          return acu + parseFloat(item.esum);
        }
        return acu + 0;
      }, 0);
    },
    surrendered() {
      return this.cashes.reduce((acu, item) => {
        if (item.surrendered !== null) {
          return acu + parseFloat(item.surrendered.amount);
        }
        return acu + 0;
      }, 0);
    }
  },
  methods: {
    initial(item) {
      let sum = Number(item.cash) + Number(item.esum);
      if (item.surrendered !== null) {
        sum += Number(item.surrendered.amount);
      }
      return `S/:${(sum - item.isum).toFixed(2)}`;
    },
    async fetch() {
      const { data } = await api.fetchByMonth(this.$store.state.month);
      this.cashes = data.values;
      if (data.values.length) {
        this.lastCash = data.values[0].cash;
      }
    },
    gooList(where, created_at) {
      const to_date = new Date(created_at);
      to_date.setDate(to_date.getDate() + 1);
      this.$store.commit("SET_RANGE", {
        from: iso(new Date(created_at)),
        to: iso(to_date)
      });

      this.$router.push({ name: where });
    },
    gooCash(date) {
      this.$store.state.date = date.slice(0, 10);
      this.$router.push({ name: "cash" });
    },
    excel() {
      api.exportToExcel().then((r) => {
        this.$downl(r.data, "Resumen de Ingresos y Egresos", ".xlsx");
      });
    }
  }
};
</script>
<style lang="scss" scoped>
.sum {
  cursor: pointer;
  u {
    font-weight: 700;
  }
  i {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
  }
  &:hover {
    transform: scale(1.1);
    transition: 0.25s ease-out;
  }
}
</style>
