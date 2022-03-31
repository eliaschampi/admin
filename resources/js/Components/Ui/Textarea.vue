<template>
  <div class="form-group" :class="{ 'has-danger': !!error }">
    <label :for="id">
      <slot>{{ label }}</slot>
    </label>
    <textarea
      :class="{ 'form-control': true }"
      :id="id"
      :name="id"
      :value="value"
      @input="updateValue"
      @change="updateValue"
      @blur="$emit('blur')"
      :disabled="disabled"
      cols="30"
      :rows="size"
    ></textarea>
    <small v-show="error" class="small text-danger">{{ error }}</small>
  </div>
</template>
<script>
export default {
  $_veeValidate: {
    name() {
      return this.id;
    },
    value() {
      return this.value;
    }
  },
  props: {
    id: String,
    value: String,
    label: {
      type: String
    },
    disabled: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      required: false
    },
    size: {
      type: String,
      default: "5"
    }
  },
  methods: {
    updateValue(e) {
      this.$emit("input", e.target.value);
    }
  }
};
</script>
<style scoped>
textarea.md-textarea {
  overflow-y: scroll;
}
</style>
