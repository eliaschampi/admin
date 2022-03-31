<template>
  <button
    :class="['btn', color, size, { 'btn-block': block }]"
    @click="$emit('pum')"
    type="button"
  >
    <template v-if="load"> espere... </template>
    <template v-else>
      <i :class="icon" v-if="icon"></i>
      <slot></slot>
    </template>
  </button>
</template>

<script>
export default {
  name: "mButton",
  props: {
    block: {
      type: Boolean,
      default: false
    },
    load: {
      type: Boolean,
      default: false
    },
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
    }
  }
};
</script>
