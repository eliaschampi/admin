<template>
  <section id="newAT">
    <m-form title="Nuevo Horario" @save="save" btn-name="Guardar">
      <div class="row p-3">
        <div class="col-md-4 vl">
          <input-finder :fullname="person_name" who="teacher" />
          <div class="form-group mt-4">
            <label for="docselect">Curso</label>
            <select
              class="form-control"
              id="docselect"
              v-model="opT.course_code"
            >
              <option
                :key="item.code"
                v-for="item in courses"
                :value="item.code"
              >
                {{ item.name }}
              </option>
            </select>
          </div>
          <div class="form-group mt-4">
            <alert :dismisable="false">
              Selecciona el grado o grupo correspondiente al horario del docente
              y curso
            </alert>
            <select
              class="form-control"
              name="modality"
              id="modality_id"
              v-model="modality"
              @change="handleChangeModality"
            >
              <option value="" disabled selected hidden>
                Selecciona una opción
              </option>
              <option value="all">Toda la institución</option>
              <option value="cycle">Ciclo o nivel académico</option>
              <option value="sts">Multiples secciones o grupos</option>
            </select>
          </div>
          <div class="form-group mt-4" v-if="modality === 'cycle'">
            <label for="level_id">Ciclo o nivel</label>
            <select class="form-control" id="level_id" @input="handleAddCycle">
              <option
                :key="item.code"
                v-for="item in cycles"
                :value="item.type"
              >
                {{ item.full_name }}
              </option>
            </select>
          </div>
          <div class="form-group mt-4" v-else-if="modality === 'sts'">
            <label for="degree_id">Agrega los grados</label>
            <div class="d-flex">
              <select class="form-control" id="degree_id">
                <option
                  :key="item.code"
                  v-for="item in sections"
                  :value="item.code"
                >
                  {{ item.code | full }}
                </option>
              </select>
              <m-button
                color="btn-icon btn-inverse-accent"
                icon="icon ion-md-add"
                @pum="handleAddSection"
              />
            </div>
            <ul style="list-style: none" class="mt-2">
              <li class="text-primary" v-for="(st, index) in opT.sts" :key="st">
                {{ st | full }}
                <span
                  class="icon ion-md-trash text-danger pointer"
                  @click="handleRemoveSection(index)"
                ></span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-8" style="position: relative">
          <m-button
            color="btn-icon btn-accent"
            data-target="#newSchedule"
            data-toggle="modal"
            icon="icon ion-md-add"
          >
          </m-button>
          <m-table :columns="columns" :data="opT.schedules" :head="false">
            <template slot="data">
              <tr :key="index" v-for="(item, index) in opT.schedules">
                <td>{{ index }}</td>
                <td>{{ days[item.day].day }}</td>
                <td>{{ item.from_time }}</td>
                <td>{{ item.to_time }}</td>
                <td>
                  <m-action
                    @action="delS(index)"
                    icon="trash"
                    color="danger"
                    tool="Borrar"
                  />
                </td>
              </tr>
            </template>
          </m-table>
          <alert :dismisable="false" type="alert-warning">
            <p class="alert-link">IMPORTANTE:</p>
            <p>
              <i class="icon ion-md-information-circle text-success"></i>
              Evita duplicar el horario de un docente.
            </p>
            <p class="text-danger mt-4">{{ errorMessage }}</p>
          </alert>
        </div>
      </div>
    </m-form>
    <add-item @save="add" :days="days"></add-item>
  </section>
</template>
<script>
import api from "@/Api/op";
import AddItem from "./AddItem.vue";
import days from "@/Data/weekDays.json";
import scApi from "@/Api/section";
import cycleApi from "@/Api/cycle";
import courseApi from "@/Api/course";
import InputFinder from "@/Components/Finder/InputFinder.vue";
import { EventBus } from "@/Helpers/bus";
export default {
  components: {
    AddItem,
    InputFinder
  },
  data() {
    return {
      errorMessage: "",
      person_name: "",
      modality: "",
      cycles: [],
      sections: [],
      courses: [],
      columns: ["#", "Día", "Desde", "Hasta", "Acciones"],
      days,
      teachers: [],
      opT: {
        teacher_dni: "",
        course_code: "",
        sts: [],
        schedules: []
      }
    };
  },
  mounted() {
    EventBus.$on("afterSelectPerson", this.addPerson);
    this.fetchCourses();
  },
  methods: {
    addPerson({ dni, name, lastname }) {
      this.person_name = `${name} ${lastname}`;
      this.opT.teacher_dni = dni;
      $("#finderModal").modal("hide");
    },
    handleAddCycle({ target: { value } }) {
      this.opT.sts = [value];
    },
    handleAddSection() {
      const { value } = document.getElementById("degree_id");
      if (this.opT.sts.indexOf(value) === -1 && this.opT.sts.length < 5) {
        this.opT.sts.push(value);
      }
    },
    handleRemoveSection(index) {
      this.opT.sts.splice(index, 1);
    },
    handleChangeModality({ target: { value } }) {
      if (value === "cycle") {
        this.fetchCycles();
      } else if (value === "all") {
        this.opT.sts = [value];
      } else if (value === "sts") {
        this.opT.sts = [];
        this.fetchSections();
      }
    },
    async fetchSections() {
      if (!this.sections.length) {
        const { data } = await scApi.fetchByYearAndBranch();
        this.sections = data.values;
      }
    },
    async fetchCourses() {
      const { data } = await courseApi.fetchAll();
      this.courses = data.courses;
    },
    async fetchCycles() {
      if (this.cycles.length === 0) {
        const { data } = await cycleApi.fetchAll();
        this.cycles = data.values;
        if (data.values.length) {
          this.handleAddCycle({
            target: { value: data.values[0].type }
          });
        }
      }
    },
    save() {
      if (!this.opT.teacher_dni) {
        this.errorMessage = "Selecciona un docente";
      } else if (!this.opT.course_code) {
        this.errorMessage = "Selecciona un curso";
      } else if (this.opT.sts.length === 0) {
        this.errorMessage = "Selecciona una sección o grupo";
      } else if (this.opT.schedules.length === 0) {
        this.errorMessage = "Establece un horario semanal";
      } else {
        this.$snack.show({
          text: "El horario será registrado",
          button: "confirmar",
          action: async () => {
            const { data } = await api.store(this.opT);
            this.$snack.success(data.message);
            this.$router.go(-1);
          }
        });
      }
    },
    add(schedule) {
      const self = {
        day: schedule.day,
        from_time: schedule.from_time,
        to_time: schedule.to_time
      };
      if (this.addS(self) > 0) {
        $("#newSchedule").modal("hide");
      }
    },
    delS(index) {
      this.opT.schedules.splice(index, 1);
    },
    addS(s) {
      const yep = this.opT.schedules.some(
        ({ day, from_time }) => day === s.day && from_time === s.from_time
      );
      if (yep) {
        return 0;
      }
      return this.opT.schedules.push(Object.assign({}, s));
    }
  }
};
</script>
