<template>
  <card id="stuatt" title="Reporte de asistencia">
    <m-button
      :disabled="!attendances.length"
      @pum="exportToExcel"
      color="btn-inverse-primary"
      class="btn-icon"
      slot="rb"
      icon="icon ion-md-cloud-download"
    />
    <m-table :columns="columns" :data="attendances" :fetch="fetchData">
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
          <td>{{ item.created_at | date }}</td>
        </attendance-row>
      </template>
    </m-table>
    <p class="text-center text-primary" slot="foot">
      {{ attendances.length }} Asistencias
    </p>

    <justification
      :code="original.code"
      :state="original.state"
      :justification="original.justification"
      @closed="jusClosed"
      @updated="jusUpdated"
    />
  </card>
</template>
<script>
import { mapGetters } from "vuex";
import api from "@/Api/attendance";
import Range from "@/Components/Ui/Range";
import { edit } from "@/Mixins/attendance";
import AttendanceRow from "./AttendanceRow.vue";
import Justification from "./Justification.vue";
import { fetchOnWatch } from "../../Mixins";
export default {
  mixins: [edit, fetchOnWatch],
  components: {
    Justification,
    Range,
    AttendanceRow
  },
  data() {
    return {
      columns: [
        "Código",
        "Fecha",
        "Ingreso Registrado",
        "Estado de Asis.",
        "Acciones"
      ]
    };
  },
  computed: {
    ...mapGetters("student", ["fullname", "dni"]),
    ...mapGetters(["range"])
  },
  methods: {
    fetchData() {
      return api
        .fetchByEntity({
          entity_identifier: this.dni,
          ...this.range
        })
        .then((r) => {
          this.attendances = r.data.values;
        });
    },
    exportToExcel() {
      const { from, to } = this.range;
      api.exportToExcel(this.dni, from, to).then((r) => {
        const name = `Asistencia ${this.fullname} Desde ${from} Hasta ${to}`;
        this.$downl(r.data, name, ".xlsx");
      });
    }
  }
};
</script>
