<template>
  <section id="inspection-form">
    <m-form :title="`${title} ${decorated_types[inspection.inspection_type]}`">
      <div class="form-row">
        <div class="col-md-6">
          <inspection-type v-model="inspection.inspection_type" />
          <hr />
          <m-textarea
            id="description"
            v-model="inspection.description"
            v-validate="'required|min:10|max:450'"
            label="Escribe aquí brevemente la descripción del incidente"
            :error="errors.first('description')"
          />
          <div class="form-group">
            <label for="dateins">¿Cuando ocurrió?</label>
            <input
              id="dateins"
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
          <template v-if="inspection.inspection_type === 'p'">
            <div class="form-group">
              <label for="dateper">Fecha de permiso</label>
              <input
                id="dateper"
                type="datetime-local"
                class="form-control"
                v-model="inspection.additional"
              />
            </div>
          </template>
        </div>
      </div>
    </m-form>
  </section>
</template>
<script>
import InputFinder from "../../Components/Finder/InputFinder.vue";
import PersonType from "../../Components/Views/PersonType.vue";
import InspectionType from "./components/InspectionType.vue";
import day from "../../Helpers/day";
export default {
  components: { InspectionType, PersonType, InputFinder },
  data() {
    return {
      title: "Registrar",
      inspection: {
        inspection_type: "p",
        entity_type: "student",
        description: "",
        created_at: ""
      },
      decorated_types: {
        p: "un permiso",
        l: "una llamada",
        r: "una requisa"
      },
      person_name: ""
    };
  },
  mounted() {
    this.inspection.created_at = day().format("YYYY-MM-DDTHH:mm");
  }
};
</script>