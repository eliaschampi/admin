<template>
  <modal
    id="updateInspection"
    :title="`Modificar ${mitype}`"
    btn-name="Modificar"
    @sub="handleSave"
  >
    <alert>
      Este(a) <i>{{ mitype }}</i> se ha registrado para el
      <span class="font-weight-medium text-secondary">{{ person.label }}</span>
      <b class="alert-link">{{ fullname }}</b>
    </alert>
    <p class="mb-2">
      <b class="text-primary">Fecha de registro: </b
      >{{ inspection.created_at | datetim }}
    </p>
    <m-textarea
      id="description"
      v-model="inspection.description"
      v-validate="'required|min:10|max:450'"
      label="Descripción"
      :error="errors.first('description')"
    />
    <template v-if="inspection.inspection_type">
      <div class="form-group mt-2">
        <label for="myadditional">
          {{ requiredDT.additional }}
        </label>
        <input
          id="myadditional"
          :type="mitype === 'permiso' ? 'date' : 'text'"
          required
          placeholder="Campo obligatorio"
          class="form-control"
          v-model="inspection.additional"
        />
      </div>

      <inspection-state
        :stateop="requiredDT.state_op"
        v-model="inspection.state"
      />
      
      <m-switch
        class="mt-4"
        v-if="inspection.inspection_type === 'l'"
        id="id_update_cel"
        text="Actualizar el numero de celular activo"
        v-model="inspection.update_person_phone"
      />
    </template>
  </modal>
</template>
<script>
import dec_types from "@/Data/decTypes.json";
import InspectionState from "./InspectionState.vue";
export default {
  components: { InspectionState },
  name: "update-inspection",
  data() {
    return {
      dec_types,
      mitype: "",
      person: "",
      fullname: "",
      inspection: {}
    };
  },
  computed: {
    requiredDT() {
      const { additional, state_op } =
        this.dec_types[this.inspection.inspection_type];
      return { additional, state_op };
    }
  },
  methods: {
    showModal(mitype, person, fullname, inspection) {
      this.mitype = mitype;
      this.person = person;
      this.fullname = fullname;
      this.inspection = inspection;
      $("#updateInspection").modal("show");
    },
    handleSave() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.$emit("save", this.inspection);
        }
      });
    }
  }
};
</script>
