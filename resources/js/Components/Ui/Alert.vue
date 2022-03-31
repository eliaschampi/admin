<template>
  <div class="alert alert-rounded alert-dismissible fade show" :class="type">
    <h4 class="alert-heading" v-show="title">{{ title }}</h4>
    <button class="close" data-dismiss="alert" type="button" v-if="dismisable">
      &times;
    </button>
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "Alert",
  props: {
    type: {
      type: String,
      default: "alert-info"
    },
    dismisable: {
      type: Boolean,
      default: true
    },
    title: {
      type: String,
      default: ""
    }
  }
};
</script>
