<template>
  <div class="card" :class="type">
    <div class="card-body">
      <div class="card-title" v-if="title">{{ title }}</div>
      <slot></slot>
    </div>
    <div class="card-footer" v-if="f">
      <slot name="foot"></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: ""
    },
    type: {
      type: String,
      default: ""
    },
    f: {
      type: Boolean,
      default: false
    }
  }
};
</script>
