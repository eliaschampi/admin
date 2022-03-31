<template>
  <modal
    id="new_cash"
    :title="title"
    btn-name="Aperturar"
    @sub="$emit('save', code)"
  >
    <m-plain label="Código:" :value="code" />
    <m-plain label="Usuario:" :value="$store.getters['user/fullname']" />
    <div class="form-group row">
      <label for="m-cash" class="col-md-3 font-weight-bold col-form-label">
        Saldo:
      </label>
      <div class="col-sm-9">
        <input
          id="m-cash"
          type="number"
          :value="lastCash"
          class="form-control form-control-lg"
          readonly
        />
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  props: {
    lastCash: {
      type: String,
      default: "0.00"
    }
  },
  computed: {
    code() {
      const date = new Date();
      let code = `CJ${this.$store.getters["user/branch_code"]}`;
      code += date.getFullYear().toString().substr(-2);
      code += "-";
      code += date.getMonth() + 1 + date.getDate().toString();
      return code;
    },
    title() {
      return `Aperturar Caja - ${new Date().toLocaleDateString()}`;
    }
  }
};
</script>
