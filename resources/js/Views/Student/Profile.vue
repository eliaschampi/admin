<template>
  <div id="student-info" class="mt-3">
    <info route="new_s" :person="student">
      <div class="d-flex align-items-center">
        <div class="font-weight-bold text-primary">Sede al que pertenece:</div>
        <div class="ml-2">
          <b>{{ $store.getters["user/branch"] }}</b>
          <span
            v-can="'S'"
            class="ml-2 text-accent pointer"
            @click="showAdvanceModal"
          >
            Cambiar de sede
          </span>
        </div>
      </div>
      <div
        class="pointer font-weight-bold text-primary mt-2"
        @click="handleRoute('payment')"
      >
        Pagos y mensualidades
      </div>
      <div
        class="pointer font-weight-bold text-primary mt-2"
        @click="handleRoute('attendance_student')"
      >
        Asistencia
      </div>
      <hr />
    </info>
    <modal
      @sub="save"
      id="admodal"
      title="Cambiar sede"
      btn-name="Confirmar"
      :disabled="!new_branch"
    >
      <div class="alert alert-warning">
        Si el estudiante esta matriculado en el presente año, la sede al que
        desea cambiar también debe haber aperturado el mismo nivel, grado y
        sección correspondiente
      </div>
      <div class="form-group">
        <label for="braselid">Sede</label>
        <select class="form-control" id="braselid" v-model="new_branch">
          <option :key="item.code" v-for="item in branches" :value="item.code">
            {{ item.name }}
          </option>
        </select>
      </div>
    </modal>
  </div>
</template>
<script>
import { mapState } from "vuex";
import Info from "../Person/Info";
import api from "../../Api/student";
import apiBranch from "../../Api/branch";
export default {
  components: { Info },
  name: "StudentProfile",
  data() {
    return {
      branches: [],
      new_branch: null
    };
  },
  computed: mapState("student", ["student"]),
  methods: {
    showAdvanceModal() {
      if (this.branches.length === 0) {
        apiBranch.fetchAll().then((r) => {
          const lcode = this.$store.getters["user/branch_code"];
          this.branches = Object.values(r.data.values).filter(
            (item) => item.code !== lcode
          );
        });
      }
      $("#admodal").modal("show");
    },
    save() {
      api
        .changeBranch({
          dni: this.student.dni,
          branch_code: this.new_branch
        })
        .then(({ data }) => {
          $("#admodal").modal("hide");
          this.$snack.success(data.message);
          this.$store.commit("student/FETCH_STUDENT", null);
          this.$router.push({ name: "student_profile" });
        });
    },
    handleRoute(router) {
      this.$router.push({
        name: router,
        params: {
          dni: this.student.dni
        }
      });
    }
  }
};
</script>
