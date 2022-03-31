<template>
  <div class="mt-3">
    <m-button
      data-toggle="modal"
      data-target="#addRelation"
      color="btn-inverse-primary"
      size="btn-sm"
    >
      Agregar apoderado
    </m-button>
    <m-table
      emptytext="Estudiante aún no poseé apoderados."
      :columns="columns"
      :data="families"
      :head="false"
    >
      <template slot="data">
        <tr :key="item.dni" v-for="item in families">
          <td>
            <router-link
              class="font-weight-medium text-primary"
              :to="{
                name: 'family_profile',
                params: { dni: item.dni }
              }"
            >
              {{ item.person.name + " " + item.person.lastname }}
            </router-link>
          </td>
          <td>{{ relation_types[item.pivot.relation_type].name }}</td>
          <td>{{ item.pivot.is_main ? "Si" : "No" }}</td>
          <td>{{ item.person.phone }}</td>
          <td>
            <span
              @click="del(item)"
              class="text-danger pointer"
              data-tooltip="Quitar"
            >
              <i class="icon ion-md-remove-circle icon-md"></i>
            </span>
          </td>
        </tr>
      </template>
    </m-table>
    <relation-modal
      title="apoderado"
      who="family"
      who_r="new_f"
      :dni="dni"
      @added="fetchData"
    />
  </div>
</template>
<script>
import relation_types from "../../Data/relationTypes.json";
import api from "../../Api/family";
import { mapGetters } from "vuex";
import { fetchData } from "../../Mixins";
import RelationModal from "../../Components/Views/RelationModal";

export default {
  mixins: [fetchData],
  components: { RelationModal },
  data() {
    return {
      families: [],
      relation_types,
      columns: [
        "Apoderado",
        "Parentesco",
        "¿Encargado Principal?",
        "Celular",
        "Acciones"
      ]
    };
  },
  computed: {
    ...mapGetters("student", ["dni"])
  },
  methods: {
    fetchData() {
      api.fetchByStudent(this.dni).then((r) => {
        this.families = r.data.values;
      });
    },
    del(item) {
      this.$snack.show({
        text: this.$confirm("exclude", "el apoderado"),
        button: "CONFIRMAR",
        action: () => {
          api.removeStudent(item.dni, this.dni).then((r) => {
            this.families.splice(this.families.indexOf(item), 1);
            this.$snack.show(r.data.message);
          });
        }
      });
    }
  }
};
</script>
