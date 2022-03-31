<template>
  <span
    @click="$emit('action')"
    :class="'pointer text-' + color"
    :data-tooltip="tool"
  >
    <i :class="'icon icon-md ion-md-' + icon"></i>
  </span>
</template>
<script>
export default {
  props: {
    icon: {
      type: String,
      default: "create"
    },
    color: {
      type: String,
      default: "primary"
    },
    tool: {
      type: String,
      default: "Modificar"
    }
  }
};
</script>
