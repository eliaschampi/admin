<template>
  <form @submit.prevent="$emit('save')" autocomplete="off" class="card">
    <!-- Nav tabs -->
    <div class="card-body">
      <ul class="m-2 nav nav-line nav-fill" role="tablist">
        <slot name="tabs"></slot>
      </ul>
      <div class="tab-content">
        <slot></slot>
      </div>
    </div>
    <div class="card-footer text-center">
      <m-submit :disabled="dis" color="btn-primary" :load="load">
        {{ btnName }}
      </m-submit>
      <m-button @pum="cancel" color="btn-light">Cancelar</m-button>
      <slot name="foot"></slot>
    </div>
  </form>
</template>
<script>
export default {
  props: {
    btnName: {
      type: String,
      default: "Guardar Registro"
    },
    title: {
      type: String,
      default: ""
    },
    dis: {
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
