<template>
  <form
    @submit.prevent="$emit('save')"
    autocomplete="off"
    class="card head-panel"
  >
    <div class="card-header">
      <div class="card-title">{{ title }}</div>
    </div>
    <div class="card-body">
      <p class="card-subtitle" v-show="subtitle">{{ subtitle }}</p>
      <slot></slot>
    </div>
    <div class="card-footer text-center">
      <m-submit :disabled="disabled" color="btn-primary" :load="load">
        {{ btnName }}
      </m-submit>
      <m-button @pum="cancel" color="btn-light">Cancelar</m-button>
    </div>
  </form>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      default: ""
    },
    subtitle: {
      type: String,
      default: ""
    },
    btnName: {
      type: String,
      default: "Guardar"
    },
    disabled: {
      type: Boolean,
      default: false
    },
    load: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    cancel() {
      this.$router.go(-1);
    }
  }
};
</script>
