<template>
  <div class="addon-group">
    <label v-if="label" :for="id">
      {{ label }}
    </label>
    <div class="input-group">
      <input
        type="text"
        :class="{ 'form-control': true, 'form-control-sm': issm }"
        :id="id"
        :name="id"
        :readonly="readonly"
        @input="updateValue"
        @change="updateValue"
        :placeholder="placeholder"
        @blur="$emit('blur')"
        :value="value"
      />
      <div class="input-group-append">
        <m-button
          @pum="$emit('onBtn')"
          color="btn-primary"
          :size="issm ? 'btn-sm' : 'btn-md'"
          :icon="icon"
        />
      </div>
    </div>
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
    value: String,
    label: {
      type: String,
      default: ""
    },
    id: {
      type: String,
      default: ""
    },
    error: {
      type: String,
      required: false
    },
    readonly: {
      type: Boolean,
      default: false
    },
    icon: {
      type: String,
      default: "icon ion-md-add icon-sm"
    },
    placeholder: String,
    issm: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    updateValue(e) {
      this.$emit("input", e.target.value);
    }
  }
};
</script>
