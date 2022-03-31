<template>
  <button
    :class="['btn', color, size, { 'btn-block': block }]"
    :disabled="load || disabled"
    type="submit"
  >
    <span v-if="load">Espere...</span>
    <template v-else>
      <i :class="icon" v-show="icon || !load"></i>
      <slot></slot>
    </template>
  </button>
</template>
<script>
export default {
  name: "mSubmit",
  props: {
    block: {
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
      default: "icon ion-ios-save"
    },
    load: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  }
};
</script>
