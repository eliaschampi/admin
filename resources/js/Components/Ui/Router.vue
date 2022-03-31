<template>
  <router-link :class="['btn is_link', color, size]" :to="to">
    <i :class="icon"></i>
    <slot></slot>
  </router-link>
</template>
<script>
export default {
  name: "mRouter",
  props: {
    color: {
      type: String,
      default: "btn-accent"
    },
    size: {
      type: String,
      default: "btn-md"
    },
    icon: {
      type: String,
      default: ""
    },
    to: [Object, String]
  }
};
</script>
