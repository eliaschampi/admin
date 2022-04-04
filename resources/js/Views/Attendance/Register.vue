<template>
  <card title="Registrar asistencia del día de hoy">
    <m-button
      @pum="handleChangePriority"
      slot="rb"
      color="btn-inverse-info btn-sm"
    >
      <b>i</b>({{ priority }})
    </m-button>
    <alert class="alert alert-info" :dismisable="false">
      Listado de Estudiantes que aún no han registrado su asistencia. Para ver a
      los estudiantes que registrarón sus asistencia
      <router-link
        :to="{
          name: 'main_attendance',
          params: { degree_code: $route.params.degree_code }
        }"
        class="alert-link text-primary"
        >Click aquí.
      </router-link>
    </alert>
    <div class="row">
      <m-table
        class="col-md-12"
        :columns="columns"
        :data="students"
        @ref="fetchData"
        v-model="buscado"
      >
        <my-section @done="fetchData" />
        <template slot="data">
          <tr :key="item.entity_identifier" v-for="item in filtered">
            <td class="text-primary font-weight-medium">
              {{ item.full_name }}
            </td>
            <td>
              <i class="icon ion-md-call icon-sm text-success"></i>
              {{ item.phone }}
            </td>
            <td>
              <select
                style="min-width: 5rem"
                class="form-control form-control-sm"
                v-model="item.state"
              >
                <option value="permiso">Permiso</option>
                <option value="falta">falta</option>
                <option value="presente">Presente</option>
                <option value="tarde">Tarde</option>
              </select>
            </td>
            <td>
              <input
                style="max-width: 7rem"
                class="form-control form-control-sm"
                max="19:00"
                min="07:00"
                type="time"
                v-model="item.entry_time"
                v-show="['presente', 'tarde'].includes(item.state)"
              />
            </td>
            <td>
              <m-button
                :disabled="disableSaveButton(item)"
                @pum="add(item)"
                color="btn-success btn-icon"
                icon="icon ion-md-checkmark-circle-outline"
              />
            </td>
          </tr>
        </template>
      </m-table>
    </div>
    <p slot="foot" class="text-center text-primary">
      {{ students.length }} estudiantes
    </p>
  </card>
</template>
<script>
import api from "../../Api/attendance";
import regApi from "../../Api/register";
import mySection from "@/Components/Views/mySection";
import { priority } from "../../Mixins/attendance";
import _ from "lodash";
export default {
  components: { mySection },
  mixins: [priority],
  data() {
    return {
      load: false,
      buscado: "",
      students: [],
      columns: [
        "Estudiante",
        "Celular",
        "Estado",
        "Hora de ingreso",
        "Registrar"
      ]
    };
  },
  computed: {
    filtered() {
      const self = this;
      return _.orderBy(
        self.students.filter((item) =>
          new RegExp(self.buscado, "i").test(item.full_name)
        ),
        "full_name"
      );
    }
  },
  methods: {
    fetchData() {
      regApi
        .fetchForAttendance(this.$store.getters["section/code"])
        .then((r) => {
          this.students = r.data.values;
        });
    },
    add(item) {
      api
        .store({ ...item, entity_type: "s", priority: this.priority })
        .then(() => {
          this.students.splice(this.students.indexOf(item), 1);
        })
        .catch((err) => {
          this.$snack.danger(err.data);
        });
    },
    disableSaveButton(item) {
      return ["presente", "tarde"].includes(item.state)
        ? item.entry_time === ""
        : false;
    }
  }
};
</script>
<style scoped>
input[type="time"] {
  font-weight: bold;
  width: 9em;
}
</style>
