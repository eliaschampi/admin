<template>
  <card title="Registro Tardanzas e Inasistencias">
    <template #rb>
      <m-button @pum="handleChangePriority" color="btn-inverse-info btn-sm">
        <b>i</b>({{ priority }})
      </m-button>
      <m-button
        @pum="print"
        :disabled="!attendances.length"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        class="px-2"
        icon="icon ion-ios-cloud-download icon-md"
      />
    </template>
    <alert :dismisable="false">
      Este panel muestra a los estudiantes, que han asistido tarde ó que han
      faltado el dia seleccionado.
    </alert>
    <div class="row">
      <m-table
        :columns="columns"
        :data="attendances"
        :fetch="fetchData"
        class="col-md-12"
        v-model="buscado"
      >
        <datepick @fetch="fetchData" />
        <template slot="data">
          <attendance-row
            :key="item.code"
            v-for="item in filtered"
            :mod="mod"
            :item="item"
            :states="states"
            @update="update"
            @cancelEdit="cancelEdit"
            @edit="edit"
            @showJusModal="showJusModal"
          >
            <td><i class="icon ion-md-folder icon-md text-accent"></i></td>
            <td>
              <person-link :person="item.person" route="student_profile" />
            </td>
            <td>
              <i class="icon ion-md-call icon-sm text-success"></i>
              {{ item.person.phone }}
            </td>
          </attendance-row>
        </template>
      </m-table>
    </div>
    <p slot="foot" class="text-center text-primary">
      {{ attendances.length }} estudiantes faltaron o llegaron tarde
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
import api from "@/Api/attendance.js";
import { edit, priority } from "@/Mixins/attendance.js";
import Datepick from "@/Components/Views/Datepick.vue";
import PersonLink from "./PersonLink.vue";
import Justification from "./Justification";
import AttendanceRow from "./AttendanceRow.vue";
export default {
  mixins: [edit, priority],
  components: { Justification, PersonLink, AttendanceRow, Datepick },
  data() {
    return {
      columns: [
        "#",
        "Estudiante",
        "Celular",
        "Ingreso Registrado",
        "Estado",
        "Aciones"
      ]
    };
  },
  computed: {
    filtered() {
      return this.attendances.filter((item) =>
        new RegExp(this.buscado, "i").test(
          [item.person.name, item.person.lastname].join()
        )
      );
    }
  },
  methods: {
    fetchData() {
      return api.absences(this.$store.state.date, this.priority).then((r) => {
        this.attendances = r.data.values;
      });
    },
    print() {
      const date = this.$store.state.date;
      api.printAbsence(date).then((r) => {
        this.$downl(r.data, `Tardanzas e Inasistencias del fecha: ${date}`);
      });
    }
  }
};
</script>
