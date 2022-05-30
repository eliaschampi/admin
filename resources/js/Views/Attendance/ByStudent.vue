<template>
  <div>
    <m-table :columns="columns" :data="attendances" :fetch="fetchData">

      <div>
        <range @fetch="fetchData">
          <m-button
            class="align-self-center"
            style="max-width: 4rem"
            @pum="handleChangePriority"
            color="btn-inverse-info btn-sm"
          >
            <b>i</b>({{ priority }})
          </m-button>
        </range>
      </div>
      
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
      <m-button
        @pum="exportToExcel"
        color="btn-inverse-primary"
        slot="foot"
        size="btn-sm"
      >
        Exportar
      </m-button>
    </m-table>

    <p class="text-center text-primary">
      Total de registros: {{ attendances.length }}
    </p>

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
import { mapGetters } from "vuex";
import api from "@/Api/attendance";
import Range from "@/Components/Ui/Range";
import { edit, priority } from "@/Mixins/attendance";
import AttendanceRow from "./AttendanceRow.vue";
import Justification from "./Justification.vue";
import { fetchOnWatch } from "../../Mixins";
export default {
  mixins: [edit, fetchOnWatch, priority],
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
          ...this.range,
          entity_identifier: this.dni,
          priority: this.priority
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
