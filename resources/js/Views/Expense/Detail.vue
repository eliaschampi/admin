<template>
  <modal id="NewDetail" btn-name="Agregar" @sub="save">
    <div class="form-row">
      <m-input
        id="Descripción"
        class="col-md-12"
        v-model="detail.description"
        v-validate="'required|max:50'"
        :error="errors.first('Descripción')"
      />
      <m-input
        id="Precio"
        type="number"
        step="0.01"
        class="col-md-6"
        v-model="detail.unit_price"
        v-validate="'required'"
        :error="errors.first('Precio')"
      />
      <m-input
        id="Cantidad"
        class="col-md-6"
        type="number"
        v-model="detail.quantity"
        v-validate="'required|min_value:1'"
        :error="errors.first('Cantidad')"
      />
      <h5 class="col-md-12">
        Total: {{ (detail.unit_price * detail.quantity) | currency }}
      </h5>
    </div>
  </modal>
</template>
<script>
export default {
  data() {
    return {
      detail: {
        quantity: "1",
        unit_price: "0.00"
      }
    };
  },
  methods: {
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.$emit("save", this.detail);
        }
      });
    }
  }
};
</script>
