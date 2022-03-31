<template>
  <section id="migration">
    <m-form
      title="Migración de Secciones y Estudiantes"
      @save="store"
      :load="load"
      btn-name="guardar"
      v-if="section"
      :disabled="registers.length === 0"
    >
      <alert type="alert-warning">
        Este módulo presenta secciones y estudiantes del grado anterior
        (solamente los estudiantes con estado <b>Finalizo</b>) para luego
        reasignarlos a las nuevas secciones que pueden ser disminuidos o
        aumentados segun se requiera.
        <br />
        Los estudiantes serán registrados con estado <b>Pendiente</b>. Para
        habilitar su estado deberan pagar su matricula correspondiente
      </alert>
      <div class="d-flex flex-wrap">
        <div
          class="item section-preview p-3 overflow-auto"
          style="max-width: 40rem"
        >
          <div class="timeline">
            <div>
              <div class="timestamp">Desde {{ last_year }}</div>
              <div class="status">
                <span class="d-block">{{ last_degre_name }}</span>
                <b class="text-primary text-small">
                  {{ registers.length }} estudiantes
                </b>
              </div>
            </div>
            <div>
              <div class="timestamp">Hasta {{ curr_year }}</div>
              <div class="status">
                <span class="d-block" v-if="degree.cycle">
                  {{ `${degree.full_name} de ${degree.cycle.full_name}` }}
                </span>
                <span class="d-block" v-else> Nuevo grado </span>
                <b class="text-primary text-small">
                  {{ actives.length }} estudiantes
                </b>
              </div>
            </div>
          </div>
          <div class="font-weight-medium">
            Agregue o disminuya las secciones según la cantidad de estudiantes
            del nuevo Grado.
          </div>
          <ul class="list-inline m-3">
            <li
              :key="item.code"
              class="list-inline-item"
              v-for="item in sections"
            >
              <span class="badge badge-info p-2">{{ item.name }}</span>
            </li>
            <li class="list-inline-item">
              <span
                @click="addS"
                class="badge badge-primary p-2 pointer"
                v-show="sections.length < 6"
              >
                <i class="icon ion-md-add"></i>
              </span>
            </li>
            <li class="list-inline-item">
              <span
                @click="subS"
                class="badge badge-accent p-2 pointer"
                v-show="sections.length > 1"
              >
                <i class="icon ion-ios-remove-circle-outline"></i>
              </span>
            </li>
          </ul>
          <p>
            Promedio de estudiantes por sección:
            <span class="badge badge-warning p-2">{{ avgs }}</span>
          </p>
        </div>
        <div class="item ml-3" style="max-width: 15rem">
          <m-input
            class="mt-4"
            id="Mensualidad"
            type="number"
            label="Nueva Mensualidad"
            v-validate="'required|max_value:1000|min_value:0'"
            :error="errors.first('Mensualidad')"
            v-model="monthly"
          />
          <div class="font-italic">
            Esta mensualidad será asignado a todos los estudiantes del nuevo
            grado. Posteriormente puede modificar individualmente.
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <m-table
          :columns="columns"
          :data="filtered"
          :load="load"
          v-model="buscado"
          class="col-md-12"
          emptytext="No hay estudiantes en esta sección."
        >
          <div class="d-flex flex-wrap align-items-center">
            <div
              class="form-group"
              style="max-width: 15rem"
              v-show="!inactives"
            >
              <label for="migsel">Seleccione una Sección</label>
              <select class="form-control" id="migsel" v-model="section">
                <option :key="item.code" v-for="item in sections" :value="item">
                  {{ item.name }}
                </option>
              </select>
            </div>
            <div class="pointer ml-3">
              <m-check
                id="modo"
                text="Mostrar estudiantes excluidos."
                v-model="inactives"
              ></m-check>
              <small class="pull-right text-muted">
                <i>Estudiantes exluidos no seran registrados</i>
              </small>
            </div>
          </div>
          <template slot="data">
            <tr v-for="item in filtered" :key="item.student_dni">
              <td>{{ item.student_dni }}</td>
              <td class="text-primary">
                {{
                  `${item.student.person.name} ${item.student.person.lastname}`
                }}
              </td>
              <td>{{ item.student.person.phone }}</td>
              <td>
                <select
                  :id="item.student_dni"
                  class="form-control"
                  v-model="item.section_code"
                >
                  <option v-for="i in sections" :key="i.code" :value="i.code">
                    {{ i.name }}
                  </option>
                </select>
              </td>
              <td>
                <span
                  class="text-danger pointer"
                  v-show="!inactives"
                  @click="item.section_code = ''"
                >
                  <i class="icon ion-ios-remove-circle-outline icon-md"></i>
                </span>
              </td>
            </tr>
          </template>
          <h5 class="font-weight-light text-center" slot="foot">
            {{ filtered.length }} Estudiantes
          </h5>
        </m-table>
      </div>
    </m-form>
    <div class="text-center" v-else>
      <p>
        No hay secciones en el grado anterior
        <b>o</b> ya se ha realizado la migración.
      </p>
      <m-router
        :to="{ name: 'section_student', params: { degree_code: degree.code } }"
      >
        Ver Estudiantes
      </m-router>
      <m-router
        :to="{ name: 'new_section', params: { degree_code: degree.code } }"
        color="btn-light"
      >
        Registrar nueva Seccion
      </m-router>
    </div>
  </section>
</template>

<script>
import { mapState } from "vuex";
import cycles from "../../../Data/cycles.json";
import sectionApi from "@/Api/section";
import api from "@/Api/register";
export default {
  data() {
    return {
      load: false,
      monthly: "0.00",
      section: null,
      curr_year: new Date().getFullYear(),
      registers: [],
      inactives: false,
      columns: ["DNI", "Apellidos y Nombres", "Telf.", "Sección", "Excluir"],
      buscado: "",
      sections: [],
      already_sections_names: [],
      last_degree_code: null,
      last_year: null,
      last_degre_name: null,
      cycles
    };
  },
  created() {
    this.fetchData(this.degree.code);
  },
  watch: {
    "$route.params.degree_code"(val) {
      this.sections = [];
      this.registers = [];
      this.section = null;
      this.fetchData(val);
    }
  },
  computed: {
    ...mapState("degree", ["degree"]),
    actives() {
      return this.registers.filter((i) => {
        return i.section_code !== "";
      });
    },
    avgs() {
      return Number(this.actives.length / this.sections.length).toFixed(0);
    },
    filtered() {
      const rex = new RegExp(this.buscado, "i");
      return this.registers.filter(({ section_code: s_code, student }) => {
        if (this.inactives) {
          return s_code === "";
        }
        return s_code === this.section.code && rex.test(student.person.name);
      });
    }
  },
  methods: {
    async fetchData(degree_code) {
      const newsections = [];
      const already_have = this.degree.sections_count > 0;

      if (already_have) {
        const { data } = await sectionApi.fetchByDegree(degree_code);
        newsections.push(...data.values);
      }
      const { data } = await sectionApi.fetchForMigrate(degree_code);
      const oldsections = data.sections;
      const regs = data.registers;

      if (already_have || oldsections.length > 0) {
        this.last_degree_code = data.last_code;
        this.last_year = data.last_code.substr(0, 4);
        this.last_degre_name = `${data.last_code.substr(-1)} de ${
          this.cycles[data.last_code.substr(4, 3)]
        }`;

        if (already_have) {
          this.already_sections_names = newsections.map((item) => item.name);
          this.sections = newsections.map((item) => ({
            code: `${this.last_degree_code}${item.name}`,
            degree_code: this.last_degree_code,
            name: item.name,
            already_have: true
          }));
        } else {
          this.sections = oldsections;
        }

        if (this.sections.length > 0) {
          this.section = this.sections[0];
          this.registers = regs;
        }

        if (this.degree.cycle) {
          this.monthly = this.degree.cycle.monthly;
        }
      } else {
        this.registers = [];
        this.section = null;
      }
    },
    addS() {
      const newS = String.fromCharCode(
        this.sections[this.sections.length - 1].name.charCodeAt(0) + 1
      );
      this.sections.push({
        code: this.last_degree_code + newS,
        degree_code: this.last_degree_code,
        name: newS,
        already_have: this.already_sections_names.some((item) => item === newS)
      });
      this.$snack.show(`Seccion: ${newS} ha sido agregado`);
    },
    subS() {
      const p = this.sections.pop();
      this.registers.forEach((item) => {
        if (item.section_code === p.code) {
          item.section_code = "";
        }
      });
      this.$snack.warning(`Seccion: ${p.name} ha sido removido`);
    },
    store() {
      this.$snack.show({
        text: this.$confirm("store", "las secciones"),
        button: "CONFIRMAR",
        action: () => {
          this.load = true;
          const sections = this.sections.filter((item) => {
            return (
              !item.already_have &&
              this.actives.some((reg) => reg.section_code === item.code)
            );
          });

          api
            .storeMany({
              sections,
              registers: this.actives,
              monthly: this.monthly,
              degree_code: this.degree.code
            })
            .then((r) => {
              this.$snack.success(r.data.message);
              this.$router.push({
                name: "sumary"
              });
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    }
  }
};
</script>
<style lang="scss" scoped>
.timeline {
  margin: 50px 0;
  list-style-type: none;
  display: flex;
  padding: 0;
  text-align: center;
  li {
    transition: all 200ms ease-in;
  }
}
.timestamp {
  width: 100%;
  margin-bottom: 1rem;
  text-align: center;
  font-weight: 500;
}
.status {
  padding: 0px 40px;
  text-align: center;
  border-top: 4px solid #3e70ff;
  position: relative;
  transition: all 200ms ease-in;
  span {
    font-weight: 600;
    padding-top: 20px;
    &:before {
      content: "";
      width: 25px;
      height: 25px;
      background-color: #e8eeff;
      border-radius: 25px;
      border: 4px solid #3e70ff;
      position: absolute;
      top: -15px;
      left: calc(50% - 12px);
    }
  }
}
</style>
