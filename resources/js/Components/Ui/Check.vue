<template>
  <div class="form-check">
    <label class="form-check-label" :for="id">
      <input
        :checked="checked"
        :disabled="dis"
        :id="id"
        @change="$emit('change', $event.target.checked)"
        class="form-check-input"
        type="checkbox"
      />
      {{ text }}
    </label>
  </div>
</template>
<script>
export default {
  name: "mCheck",
  model: {
    prop: "checked",
    event: "change"
  },
  props: {
    checked: Boolean,
    dis: {
      type: Boolean,
      default: false
    },
    id: {
      type: String,
      default: "mycheck"
    },
    text: {
      type: String,
      default: ""
    }
  }
};
</script>
