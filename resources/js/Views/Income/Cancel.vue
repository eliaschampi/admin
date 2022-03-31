<template>
  <modal
    :title="'Anulando el comprobante: ' + serie"
    id="cancel"
    btn-name="Anular"
    @sub="save"
  >
    <div class="form-row">
      <m-input
        class="col-md-12"
        id="Justification"
        v-model="justification"
        label="¿Por qué anula este Comprobante?"
        v-validate="'required|min:5|max:50'"
        :error="errors.first('Justification')"
      />
    </div>
  </modal>
</template>
<script>
export default {
  props: {
    serie: String
  },
  data() {
    return {
      justification: ""
    };
  },
  methods: {
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.$emit("save", this.justification);
        }
      });
    }
  }
};
</script>
