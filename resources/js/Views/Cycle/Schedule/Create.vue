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
          <div class="form-group">
            <label for="secselec">Sección</label>
            <select
              class="form-control"
              name="seccion"
              id="secselec"
              v-model="opT.section_code"
              v-validate="'required'"
            >
              <option
                :key="item.code"
                v-for="item in sections"
                :value="item.code"
              >
                {{ item.name }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('seccion')"
              >{{ errors.first("seccion") }}
            </span>
          </div>
          <div class="form-group">
            <label for="docselect">Docente</label>
            <select
              class="form-control"
              name="docente"
              id="docselect"
              v-model="opT.teacher_dni"
              v-validate="'required'"
            >
              <option
                :key="item.dni"
                v-for="item in teachers"
                :value="item.dni"
              >
                {{ item.fullname }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('docente')"
              >{{ errors.first("docente") }}
            </span>
          </div>
          <div class="form-group">
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
              Mantener solamente 1 docente de aula para cada sección de
              Primaria.
            </p>
            <p class="my-0">
              <i
                class="icon ion-ios-information-circle icon-sm text-success"
              ></i>
              Evita duplicar el horario de un docente, ya que podría traer
              problemas a la hora de registrar la asistencia
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
import { mapActions, mapState } from "vuex";
import days from "@/Data/weekDays.json";
import api from "@/Api/op";
import courseApi from "../../../Api/course";
import t_api from "../../../Api/teacher";
import AddItem from "./AddItem.vue";
export default {
  components: {
    AddItem
  },
  data() {
    return {
      errorMessage: "",
      courses: [],
      columns: ["#", "Día", "Desde", "Hasta", "Acciones"],
      days,
      teachers: [],
      opT: {
        teacher_dni: "",
        course_code: "",
        section_code: "",
        schedules: []
      }
    };
  },
  mounted() {
    const { degree_code } = this.$route.params;
    const spe = degree_code.substr(4, 3);
    if (!this.sections.length) {
      this.fetchSections(degree_code);
    }
    this.fetchData(spe);
    courseApi.fetchAll().then((r) => {
      this.courses = r.data.courses.filter((item) => {
        return [spe, "OTR"].includes(item.type);
      });
    });
  },
  watch: {
    "$route.params.degree_code"(val) {
      const spe = val.substr(4, 3);
      this.fetchData(spe);
      this.fetchSections(val);
    }
  },
  computed: {
    ...mapState("section", ["sections"])
  },
  methods: {
    ...mapActions({
      fetchSections: "section/fetchAll"
    }),
    fetchData(spe) {
      t_api.fetchBySpe(spe).then((r) => {
        this.teachers = r.data.values.map((item) => ({
          dni: item.dni,
          fullname: `${item.person.name} ${item.person.lastname}`
        }));
      });
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
