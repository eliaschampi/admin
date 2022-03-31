<template>
  <div class="form-group" :class="{ 'has-danger': !!error }">
    <slot></slot>
    <label :data-error="error" :for="id">
      {{ label ? label : id }}
    </label>
    <input
      :type="type"
      :id="id"
      :name="id"
      :value="value"
      @input="updateValue"
      @change="updateValue"
      @blur="$emit('blur')"
      :disabled="disabled"
      :placeholder="placeholder"
      :step="step"
      :class="{ 'form-control': true, 'form-control-sm': issm }"
    />
    <span class="text-danger mt-0 small" v-show="error">{{ error }}</span>
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
    value: [String, Number],
    label: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      required: false
    },
    issm: {
      type: Boolean,
      default: false
    },
    placeholder: String,
    step: String,
    type: {
      type: String,
      default: "text",
      validator: (val) => {
        return (
          ["time", "text", "password", "email", "number", "date"].indexOf(
            val
          ) !== -1
        );
      }
    }
  },

  methods: {
    updateValue(e) {
      this.$emit("input", e.target.value);
    }
  }
};
</script>
