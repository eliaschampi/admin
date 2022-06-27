<template>
  <section id="newAT">
    <m-form
      title="Nuevo Horario"
      @save="save"
      btn-name="Guardar"
      :disabled="!opT.schedules.length"
    >
      <div class="row p-3">
        <div class="col-md-4 vl">
          <input-finder :fullname="person_name" who="teacher" />
          <div class="form-group mt-4">
            <label for="docselect">Curso</label>
            <select
              class="form-control"
              name="curso"
              id="docselect"
              v-model="opT.course_code"
              v-validate="'required'"
            >
              <option
                :key="item.code"
                v-for="item in courses"
                :value="item.code"
              >
                {{ item.name }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('curso')"
              >{{ errors.first("curso") }}
            </span>
          </div>
          <div class="form-group mt-4">
            <label for="modality_id">Modalidad</label>
            <select
              class="form-control"
              name="modality"
              id="modality_id"
              v-model="modality"
              @change="handleChangeModality"
            >
              <option value="none">Selecciona una modalidad</option>
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
                @pum="handleAddCycle"
              />
            </div>
            <ul style="list-style: none" class="mt-2">
              <li class="text-primary" v-for="st in opT.sts" :key="st">
                {{ st | full }}
                <span class="icon ion-md-trash text-danger pointer"></span>
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
                    @action="delS(item)"
                    icon="trash"
                    color="danger"
                    tool="Borrar"
                  />
                </td>
              </tr>
            </template>
          </m-table>
          <alert :dismisable="false" type="alert-warning">
            <p class="my-0">
              <span class="alert-link">Sugerencias:</span>
            </p>
            <p class="my-0">
              <i
                class="icon ion-ios-information-circle icon-sm text-success"
              ></i>
              Evita duplicar el horario de un docente.
            </p>
            <p class="text-accent">{{ errorMessage }}</p>
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
      modality: "none",
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
    handleAddCycle(event) {
      if (this.modality === "cycle") {
        this.opT.sts = [event.target.value];
      } else {
        this.opT.sts.push(event.target.value);
      }
    },
    handleChangeModality({ target: { value } }) {
      if (value === "cycle") {
        this.fetchCycles();
      } else if (value === "all") {
        this.opT.sts = [value];
      } else if (value === "sts") {
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
      this.$validator.validateAll().then((r) => {
        if (r && this.opT.schedules.length > 0) {
          this.$snack.show({
            text: "El horario será registrado",
            button: "continuar",
            action: () => {
              api
                .store(this.opT)
                .then(() => {
                  this.$router.go(-1);
                })
                .catch((error) => {
                  if (error.code === 422) {
                    this.errorMessage = "Este horario ya ha sido registrado";
                  }
                });
            }
          });
        }
      });
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
    delS(item) {
      this.opT.schedules.splice(this.opT.schedules.indexOf(item), 1);
    },
    addS(self) {
      let poc = -1;
      if (this.opT.schedules.length) {
        this.opT.schedules.forEach((i, index) => {
          if (i.day === self.day && i.from_time === self.from_time) {
            poc = index;
          }
        });
      }
      if (poc === -1) {
        return this.opT.schedules.push(Object.assign({}, self));
      }
      return 0;
    }
  }
};
</script>
