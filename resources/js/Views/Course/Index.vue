<template>
  <section id="course">
    <card title="Registro de Cursos">
      <m-button
        @pum="show"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        class="px-2"
        slot="rb"
      >
        <i class="icon ion-md-add icon-md"></i>
      </m-button>
      <div class="row">
        <m-table
          :columns="columns"
          :data="courses"
          :fetch="fetchAll"
          :head="false"
          class="col-md-12"
        >
          <template slot="data">
            <tr :key="item.code" v-for="item in courses">
              <td>{{ item.code }}</td>
              <td>{{ types[item.type] }}</td>
              <td>{{ item.name }}</td>
              <td>
                <m-action @action="edit(item)" />
                <m-action
                  @action="delC(item)"
                  icon="trash"
                  color="danger"
                  tool="Eliminar"
                />
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <p slot="foot" class="text-center">
        <span class="font-weight-bold text-muted">
          Cursos: {{ courses.length }}
        </span>
      </p>
    </card>
    <modal :btnName="btnName" :title="title" @sub="save" id="course_modal">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="cmodsele">Seleccione una modalidad</label>
          <select
            class="form-control"
            id="cmodsele"
            name="Modalidad"
            v-validate="'required'"
            v-model="course.type"
          >
            <option
              :key="item.code"
              v-for="item in typesMod"
              :value="item.code"
            >
              {{ item.name }}
            </option>
          </select>
          <span class="small text-danger" v-show="errors.has('Modalidad')">
            {{ errors.first("Modalidad") }}
          </span>
        </div>
        <m-input
          class="col-md-6"
          id="Curso"
          v-model="course.name"
          v-validate="'required|min:2|max:50'"
          label="Nombre del Curso"
          :error="errors.first('Curso')"
        />
      </div>
    </modal>
  </section>
</template>
<script>
import mainApi from "../../Api/main";
import api from "../../Api/course";
export default {
  data() {
    return {
      course: {},
      courses: [],
      types: [],
      title: "Registrar un nuevo Curso",
      btnName: "Guardar",
      option: "create",
      columns: ["Código", "Modalidad", "Curso", "Acciones"]
    };
  },
  created() {
    mainApi.fetchByTagsPlucked("cs.d_cy.m").then((r) => {
      this.types = r.data.configs;
    });
  },
  computed: {
    typesMod() {
      return Object.keys(this.types).map((key) => {
        return { code: key, name: this.types[key] };
      });
    }
  },
  methods: {
    fetchAll() {
      return api.fetchAll().then((r) => {
        this.courses = r.data.courses;
      });
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.store(this.course)
            .then((r) => {
              $("#course_modal").modal("hide");
              this.$snack.success(r);
              this.course = {};
            })
            .catch((error) => {
              if (error.code === 422) {
                this.$setErrors(error.data, ["name", "Curso"]);
              }
            });
        }
      });
    },
    async store(course) {
      if (this.option === "create") {
        const { data } = await api.set(course);
        this.courses.push(data.course);
        return data.res;
      }
      const { data } = await api.update(course);
      const item = this.courses.find((i) => i.code === course.code);
      this.courses.splice(this.courses.indexOf(item), 1, course);
      return data.res;
    },
    delC(course) {
      this.$snack.show({
        text: this.$confirm("delete", "el curso seleccionado"),
        button: "CONFIRMAR",
        action: () => {
          api.del(course.code).then((r) => {
            this.courses.splice(this.courses.indexOf(course), 1);
            this.$snack.show(r.data);
          });
        }
      });
    },
    edit(course) {
      this.option = "edit";
      this.course = Object.assign({}, course);
      this.btnName = "Guardar Cambios";
      this.title = "Modificar un Curso";
      $("#course_modal").modal("show");
    },
    show() {
      this.option = "create";
      this.course = {};
      this.btnName = "Guardar";
      this.title = "Registrar Nuevo Curso";
      $("#course_modal").modal("show");
    }
  }
};
</script>
