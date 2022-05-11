<template>
  <section id="inspection-form">
    <m-form
      :title="`${title} ${dec_types[inspection.inspection_type].label}`"
      @save="save"
    >
      <div class="form-row">
        <div class="col-md-6">
          <inspection-type v-model="inspection.inspection_type" />
          <hr />
          <m-textarea
            id="description"
            v-model="inspection.description"
            v-validate="'required|min:10|max:450'"
            :label="descriptiontitle"
            :error="errors.first('description')"
          />
          <div class="form-group">
            <label for="dateins">¿Cuando ocurrió?</label>
            <input
              id="dateins"
              required
              type="datetime-local"
              class="form-control"
              v-model="inspection.created_at"
            />
          </div>
        </div>
        <div class="col-md-6">
          <person-type v-model="inspection.entity_type" />
          <hr />
          <div>Selecciona</div>
          <input-finder :fullname="person_name" :who="inspection.entity_type" />
          
          <div class="form-group mt-3">
            <label for="myadditional">
              {{ dec_types[inspection.inspection_type].additional }}
            </label>
            <input
              id="myadditional"
              :type="inspection.inspection_type === 'p' ? 'date' : 'text'"
              required
              placeholder="Campo obligatorio"
              class="form-control"
              v-model="inspection.additional"
            />
          </div>

          <inspection-state
            :stateop="dec_types[inspection.inspection_type].state_op"
            v-model="inspection.state"
          />
        </div>
      </div>
    </m-form>
  </section>
</template>
<script>
import InputFinder from "../../Components/Finder/InputFinder.vue";
import PersonType from "../../Components/Views/PersonType.vue";
import InspectionType from "./components/InspectionType.vue";
import InspectionState from "./components/InspectionState.vue";
import day from "../../Helpers/day";
import dec_types from "../../Data/decTypes";
import { EventBus } from "../../Helpers/bus";
import api from "../../Api/inspection";
export default {
  components: { InspectionType, PersonType, InputFinder, InspectionState },
  data() {
    return {
      title: "Registrar",
      inspection: {
        inspection_type: "p",
        entity_type: "student",
        description: "",
        created_at: "",
        state: "a"
      },
      dec_types,
      person_name: ""
    };
  },
  mounted() {
    EventBus.$on("afterSelectPerson", this.addPerson);
    this.inspection.created_at = day().format("YYYY-MM-DDTHH:mm");
  },
  watch: {
    "inspection.inspection_type"(val) {
      this.inspection.additional = "";
      this.inspection.state = {
        p: "a",
        r: "r",
        l: "c"
      }[val];
    },
    "inspection.entity_type"() {
      this.person_name = "";
      this.inspection.entity_identifier = null;
    }
  },
  computed: {
    isedit() {
      return this.$route.params.code;
    },
    descriptiontitle() {
      return `Descripción breve ${
        this.dec_types[this.inspection.inspection_type].state_op.title
      }`;
    }
  },
  methods: {
    addPerson(person) {
      this.person_name = `${person.name} ${person.lastname}`;
      this.inspection.entity_identifier = person.dni;
      $("#finderModal").modal("hide");
    },
    store(data) {
      if (data.code) {
        return api.update(data, data.code);
      }
      return api.store(data);
    },
    async save() {
      if (!this.inspection.entity_identifier) {
        this.$snack.show("Falta seleccionar estudiante o apoderado");
        return;
      }

      const { data } = await this.store(this.inspection);

      this.$snack.success(data.message);

      this.$router.push({ name: "cedp" });
    }
  }
};
</script>