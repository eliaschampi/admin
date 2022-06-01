<template>
  <card title="Asistencia por sección y fecha">
    <template #rb>
      <m-button @pum="handleChangePriority" color="btn-inverse-info btn-sm">
        <b>i</b>({{ priority }})
      </m-button>
      <m-button
        @pum="excel"
        :disabled="!attendances.length"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-md-cloud-download"
      />
    </template>
    <div class="row">
      <m-table
        class="col-md-12"
        :columns="columns"
        :data="attendances"
        @ref="fetchData"
        :load="load"
        v-model="buscado"
      >
        <div class="d-flex">
          <my-section @done="fetchData" />
          <datepick issm="true" class="ml-2" @fetch="fetchData" />
        </div>
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
      {{ attendances.length }} estudiantes registraron su asistencia
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
import api from "@/Api/attendance";
import { edit, priority } from "@/Mixins/attendance";
import mySection from "@/Components/Views/mySection";
import Datepick from "@/Components/Views/Datepick";
import Justification from "./components/Justification.vue";
import AttendanceRow from "./components/AttendanceRow.vue";
import PersonLink from "./components/PersonLink.vue";
export default {
  mixins: [edit, priority],
  components: {
    AttendanceRow,
    Justification,
    Datepick,
    mySection,
    PersonLink
  },
  data() {
    return {
      columns: [
        "#",
        "Estudiante",
        "Celular",
        "Hora de ingreso",
        "Estado",
        "Acciones"
      ],
      load: false
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
      this.load = true;
      return api
        .fetchBySection({
          section_code: this.$store.getters["section/code"],
          date: this.$store.state.date,
          priority: this.priority
        })
        .then((r) => {
          this.attendances = r.data.values;
          this.load = false;
        });
    },
    excel() {
      const s_code = this.$store.getters["section/code"];
      const date = this.$store.state.date;
      api.exportToExcelBySection(s_code, date, this.priority).then((r) => {
        const name = `Asistencia ${s_code} fecha ${date}`;
        this.$downl(r.data, name, ".xlsx");
      });
    }
  }
};
</script>
