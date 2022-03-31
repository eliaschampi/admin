<template>
  <div class="mt-5">
    <card title="Estudiantes" :f="false">
      <m-button
        data-toggle="modal"
        data-target="#addRelation"
        color="btn-inverse-primary"
        size="btn-sm"
        slot="rb"
        icon="icon ion-md-add"
      >
        Agregar
      </m-button>
      <m-table
        :columns="[
          'Apoderado es:',
          '¿Encargado Principal?',
          'Estudiante',
          'Acciones'
        ]"
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
            <td>
              <m-action
                @action="rmS(item)"
                icon="remove-circle"
                color="danger"
                tool="Excluir"
              />
            </td>
          </tr>
        </template>
      </m-table>
    </card>
    <relation-modal
      title="estudiante"
      who="student"
      who_r="new_s"
      :dni="dni"
      @added="fetchData"
    />
  </div>
</template>
<script>
import api from "../../Api/family";
import { fetchData } from "../../Mixins";
import RelationModal from "../../Components/Views/RelationModal";
import relation_types from "../../Data/relationTypes.json";
export default {
  name: "StudentList",
  components: { RelationModal },
  mixins: [fetchData],
  data() {
    return {
      students: [],
      relation_types
    };
  },
  computed: {
    dni() {
      return this.$store.getters["family/dni"];
    }
  },
  methods: {
    rmS(item) {
      this.$snack.show({
        text: this.$confirm("exclude", "el estudiante"),
        button: "CONFIRMAR",
        action: () => {
          api.removeStudent(this.dni, item.dni).then((r) => {
            this.students.splice(this.students.indexOf(item), 1);
            this.$snack.show(r.data.message);
          });
        }
      });
    },
    fetchData() {
      api.fetchStudents(this.$route.params.dni).then((r) => {
        this.students = r.data.values;
      });
    }
  }
};
</script>
