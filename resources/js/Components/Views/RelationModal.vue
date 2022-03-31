<template>
  <modal
    @sub="save"
    btn-name="Agregar"
    id="addRelation"
    @opened="handleModalOpened"
    :title="`Agregar ${title}`"
  >
    <finder @input="handleSelectPerson" />
    <template v-if="selected">
      <div class="form-group">
        <label for="dayselect" class="font-weight-medium text-primary">
          ¿Que parentezco tiene el apoderado?
        </label>
        <select
          class="form-control"
          id="dayselect"
          v-model="relation.relation_type"
        >
          <option
            :key="item.code"
            v-for="item in relation_types"
            :value="item.code"
          >
            {{ item.name }}
          </option>
        </select>
      </div>
      <m-check
        id="isMain"
        text="El apoderado es encargado Principal"
        v-model="relation.is_main"
      />
    </template>
    <template v-else>
      <p class="text-muted text-center mt-2">
        {{ title }} no seleccionado
        <router-link :to="{ name: who_r }" target="_blank">
          Registrar Nuevo
        </router-link>
      </p>
    </template>
  </modal>
</template>
<script>
import relation_types from "../../Data/relationTypes.json";
import Finder from "../../Components/Finder/Finder";
import api from "../../Api/family";
export default {
  props: ["title", "who", "who_r", "dni"],
  components: { Finder },
  data() {
    return {
      relation_types,
      selected: null,
      relation: {
        relation_type: "",
        is_main: false
      }
    };
  },
  methods: {
    handleSelectPerson(person) {
      this.selected = person;
    },
    handleModalOpened() {
      this.$store.dispatch("updateSfmOption", {
        who: this.who,
        mode: 2,
        only_current_reg: false,
        only_current_branch: false,
        after: () => {}
      });
    },
    save() {
      if (this.relation.relation_type) {
        const payload = {
          family_dni: "",
          student_dni: ""
        };
        if (this.who === "student") {
          payload.family_dni = this.dni;
          payload.student_dni = this.selected.dni;
        } else {
          payload.family_dni = this.selected.dni;
          payload.student_dni = this.dni;
        }
        api
          .addStudent({
            ...payload,
            ...this.relation
          })
          .then((res) => {
            $("#addRelation").modal("hide");
            this.$snack.success(res.data.message);
            this.$emit("added");
          })
          .catch(() => {
            this.$snack.danger("Apoderado ya ha sido agregado");
          });
      }
    }
  }
};
</script>
