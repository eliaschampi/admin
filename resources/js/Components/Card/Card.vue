<template>
  <div class="card head-panel">
    <div class="card-header">
      <div class="float-right card-action">
        <slot name="rb"></slot>
      </div>
      <div class="card-title">
        <i class="text-primary ion" :class="icon"></i> {{ title }}
      </div>
      <slot name="subtitle"></slot>
    </div>
    <div class="card-body">
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
    icon: {
      type: String,
      default: "ion-md-folder"
    },
    f: {
      type: Boolean,
      default: true
    }
  }
};
</script>
