<template>
  <div>
    <div class="mt-3 text-primary">Establece el estado {{ stateop.title }}</div>
    <div class="form-check mt-2 ml-2">
      <input
        class="form-check-input"
        type="radio"
        id="ins_state_a"
        :checked="value === fval"
        name="state_code"
        :value="fval"
        @change="$emit('input', fval)"
      />
      <label
        class="form-check-label font-weight-medium text-success"
        for="ins_state_a"
      >
        {{ stateop.first[1] }}
      </label>
    </div>
    <div class="form-check mt-2 ml-2">
      <input
        class="form-check-input"
        type="radio"
        id="ins_state_i"
        name="state_code"
        :checked="value === sval"
        :value="sval"
        @change="$emit('input', sval)"
      />
      <label
        class="form-check-label font-weight-medium text-warning"
        for="ins_state_i"
      >
        {{ stateop.second[1] }}
      </label>
    </div>
  </div>
</template>
<script>
export default {
  name: "inspection-type",
  props: {
    stateop: {
      type: Object,
      default: () => {}
    },
    value: String
  },
  computed: {
    fval() {
      return this.stateop.first[0];
    },
    sval() {
      return this.stateop.second[0];
    }
  }
};
</script>
