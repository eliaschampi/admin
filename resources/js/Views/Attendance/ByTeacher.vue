<template>
  <div id="teacher-attendance" class="mt-3">
    <alert>
      El horario de ingreso para los docentes es {{ entry_time }} con una
      tolerancia de {{ tolerance }} minutos.
    </alert>
    <m-table
      :columns="columns"
      :data="attendances"
      :fetch="fetchData"
      v-model="buscado"
    >
      <range @fetch="fetchData" />
      <template slot="data">
        <attendance-row
          :key="item.code"
          v-for="item in attendances"
          :mod="mod"
          :item="item"
          :states="states"
          @update="update"
          @cancelEdit="cancelEdit"
          @edit="edit"
          @showJusModal="showJusModal"
        >
          <td>{{ item.code }}</td>
          <td>{{ item.created_at | datetim }}</td>
          <td>
            <span v-show="item.delay"> {{ item.delay }} minutos</span>
          </td>
        </attendance-row>
        <tr>
          <td colspan="2" class="text-primary">Total de minutos de tardanza</td>
          <td colspan="4">{{ total_delay }} minutos</td>
        </tr>
      </template>
    </m-table>
    <div class="d-flex justify-content-between">
      <span class="font-weight-bold text-muted">
        Cantidad de Asistencias: {{ attendances.length }}
      </span>
      <m-button
        @pum="excel"
        :disabled="!attendances.length"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-ios-cloud-download"
      />
    </div>
    <justification
      :code="original.code"
      :state="original.state"
      :justification="original.justification"
      @closed="jusClosed"
      @updated="jusUpdated"
    />
  </div>
</template>
<script>
import { mapState, mapGetters } from "vuex";
import api from "@/Api/attendance";
import Range from "@/Components/Ui/Range";
import { edit } from "@/Mixins/attendance";
import AttendanceRow from "./AttendanceRow.vue";
import Justification from "./Justification.vue";
import { fetchOnWatch } from "../../Mixins";
import { diffToDate, dparseFromFormat } from "../../Helpers/day";
export default {
  mixins: [edit, fetchOnWatch],
  components: { Range, AttendanceRow, Justification },
  data() {
    return {
      columns: [
        "Código",
        "Fecha y hora",
        "Tardanza",
        "Ingreso registrado",
        "Estado de Asis.",
        "Acciones"
      ],
      entry_time: window.Laravel.entry_time,
      tolerance: window.Laravel.tolerance,
      total_delay: 0,
      priority: 1
    };
  },
  computed: {
    ...mapGetters("teacher", ["dni", "fullname"]),
    ...mapState(["range"])
  },
  methods: {
    fetchData() {
      this.total_delay = 0;
      return api
        .fetchByEntity({
          ...this.range,
          entity_identifier: this.dni,
          priority: this.priority
        })
        .then((r) => {
          const values = r.data.values;
          const atts = [];
          values.forEach((item) => {
            let delay = 0;
            if (item.entry_time) {
              const from = dparseFromFormat(this.entry_time, "HH:mm");
              const d = dparseFromFormat(item.entry_time, "HH:mm:ss");
              delay = diffToDate(from, d, "m") - Number(this.tolerance);
            }
            if (delay > 0) {
              item.delay = delay;
              this.total_delay += delay;
            }
            atts.push(item);
          });
          this.attendances = atts;
        });
    },
    excel() {
      const { from, to } = this.range;
      api
        .exportToExcel(this.dni, from, to)
        .then((r) => {
          const name = `Asistencia ${this.fullname} ${from} Hasta ${to}`;
          this.$downl(r.data, name, ".xlsx");
        });
    }
  }
};
</script>
