<template>
  <div class="form-check">
    <input
      :id="value"
      type="radio"
      class="form-check-input"
      :name="name"
      @change="update"
      :value="value"
    />
    <label class="form-check-label" :for="value">{{ label }}</label>
  </div>
</template>
<script>
export default {
  model: {
    prop: "checked",
    event: "change"
  },
  props: {
    label: {
      type: String,
      default: ""
    },
    value: {
      type: [Boolean, String],
      default: ""
    },
    name: {
      type: String,
      default: ""
    }
  },
  methods: {
    update() {
      this.$emit("change", this.value);
    }
  }
};
</script>
