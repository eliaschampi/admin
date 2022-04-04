<template>
  <card title="Asistencia por sección y fecha">
    <m-button
      @pum="handleChangePriority"
      slot="rb"
      color="btn-inverse-info  btn-sm"
    >
      <b>i</b>({{ priority }})
    </m-button>
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
import { edit } from "@/Mixins/attendance";
import mySection from "@/Components/Views/mySection";
import Justification from "./Justification.vue";
import AttendanceRow from "./AttendanceRow.vue";
import Datepick from "@/Components/Views/Datepick";
import api from "@/Api/attendance";
import PersonLink from "./PersonLink.vue";
export default {
  mixins: [edit],
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
      load: false,
      priority: 1
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
    handleChangePriority() {
      this.priority = this.priority < 3 ? this.priority + 1 : 1;
      this.$snack.show(`Ingreso Nro ${this.priority} ha sido seleccionado`);
    },
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
    }
  }
};
</script>
