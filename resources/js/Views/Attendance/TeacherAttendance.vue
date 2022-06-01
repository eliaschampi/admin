<template>
  <section id="ta">
    <card title="Asistencia diaria de docentes">
      <alert>
        El horario de ingreso para los docentes es {{ entry_time }} con una
        tolerancia de {{ tolerance }} minutos.
      </alert>
      <m-table
        :columns="columns"
        :data="attendances"
        :fetch="fetchData"
        :load="load"
        v-model="buscado"
      >
        <datepick issm="true" @fetch="fetchData" />

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
              <person-link :person="item.person" route="teacher_profile" />
            </td>
            <td>
              <i class="icon ion-md-call icon-sm text-success"></i>
              {{ item.person.phone }}
            </td>
          </attendance-row>
        </template>
      </m-table>
      <p slot="foot" class="text-center">
        <span class="font-weight-bold text-muted">
          Cantidad de registros {{ attendances.length }}
        </span>
      </p>
    </card>
    <justification
      :code="original.code"
      :state="original.state"
      :justification="original.justification"
      @closed="jusClosed"
      @updated="jusUpdated"
    />
  </section>
</template>
<script>
import api from "@/Api/attendance";
import { edit } from "@/Mixins/attendance";
import Datepick from "@/Components/Views/Datepick";
import Justification from "./components/Justification.vue";
import AttendanceRow from "./components/AttendanceRow.vue";
import PersonLink from "./components/PersonLink.vue";
export default {
  mixins: [edit],
  components: {
    Justification,
    AttendanceRow,
    PersonLink,
    Datepick
  },
  data() {
    return {
      columns: [
        "#",
        "Docente",
        "Celular",
        "Hora de ingreso",
        "Estado",
        "Acciones"
      ],
      load: false,
      entry_time: window.Laravel.entry_time,
      tolerance: window.Laravel.tolerance
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
      return api.fetchForTeacherByDate(this.$store.state.date).then((r) => {
        this.attendances = r.data.values;
        this.load = false;
      });
    }
  }
};
</script>
