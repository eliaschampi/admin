<template>
  <div id="family">
    <card title="Registro de Apoderados">
      <m-router
        :to="{ name: 'new_f' }"
        color="btn-inverse-accent btn-icon"
        slot="rb"
        icon="icon ion-md-add"
      />
      <div class="row">
        <m-table
          :columns="columns"
          :data="families"
          @ref="fetchData"
          :load="load"
          class="col-md-12"
          v-model="buscado"
        >
          <my-section @done="fetchData" />
          <template slot="data">
            <tr :key="item.dni" v-for="item in filtered">
              <td>{{ item.dni }}</td>
              <td>
                <router-link
                  :to="{
                    name: 'family_profile',
                    params: { dni: item.dni }
                  }"
                >
                  <b>{{ `${item.person.name} ${item.person.lastname}` }}</b>
                </router-link>
              </td>
              <td>
                <b>{{ item.students_count }}</b>
              </td>
              <td>
                <b>{{ item.person.phone }}</b>
              </td>
              <td>
                <b>{{ item.telephone }}</b>
              </td>
              <td>
                <m-button
                  size="btn-sm"
                  @pum="showStudents(item)"
                  color="btn-inverse-secondary"
                >
                  Estudiantes
                </m-button>
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Total: {{ families.length }} apoderados
      </p>
    </card>
    <modal
      id="studentoffamily"
      btnName="Cerrar"
      title="Estudiantes"
      :with-form="false"
    >
      <m-table
        :columns="['Apoderado es:', '¿Encargado Principal?', 'Estudiante']"
        :data="students"
        :head="false"
        emptytext="Aún no pertenece a ningún Estudiante."
      >
        <template slot="data">
          <tr :key="item.dni" v-for="item in students">
            <td>{{ relation_types[item.pivot.relation_type].name }}</td>
            <td>{{ item.pivot.is_main ? "Si" : "No" }}</td>
            <td>
              <router-link
                class="font-weight-medium"
                :to="{
                  name: 'student_profile',
                  params: { dni: item.dni }
                }"
              >
                {{ `${item.person.name} ${item.person.lastname}` }}
              </router-link>
            </td>
          </tr>
        </template>
      </m-table>
    </modal>
  </div>
</template>

<script>
import api from "@/Api/family";
import mySection from "../../Components/Views/mySection";
import relation_types from "../../Data/relationTypes.json";
export default {
  components: { mySection },
  data() {
    return {
      buscado: "",
      families: [],
      students: [],
      relation_types,
      load: false,
      columns: [
        "DNI",
        "Apellidos y Nombre",
        "Hijos(as)",
        "Celular",
        "Telf.",
        "Acciones"
      ]
    };
  },
  computed: {
    filtered() {
      return this.families.filter((item) =>
        new RegExp(this.buscado, "i").test(
          [item.person.name, item.person.lastname].join()
        )
      );
    }
  },
  methods: {
    fetchData() {
      this.load = true;
      api.fetchBySection(this.$store.getters["section/code"]).then((r) => {
        this.families = r.data.values;
        this.load = false;
      });
    },
    showStudents(item) {
      $("#studentoffamily").modal("show");
      if (item.students_count) {
        api.fetchStudents(item.dni).then((r) => {
          this.students = r.data.values;
        });
      }
    }
  }
};
</script>
