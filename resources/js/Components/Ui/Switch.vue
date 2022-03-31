<template>
  <div class="d-flex">
    <div class="switch mr-2">
      <input
        :checked="checked"
        type="checkbox"
        :disabled="dis"
        :id="id"
        @change="$emit('change', $event.target.checked)"
      />
      <label :for="id" class="success">Toggle</label>
    </div>
    <label :for="id">
      {{ text }}
    </label>
  </div>
</template>
<script>
export default {
  name: "mSwitch",
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
