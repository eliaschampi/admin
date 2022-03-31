<template>
  <div class="auth-form-transparent text-left p-3">
    <div class="d-flex">
      <div class="brand-logo mr-2">
        <img src="/img/carrion.svg" style="max-width: 60px" alt="logo" />
      </div>
      <div class="con">
        <h4 class="text-primary">{{ title }}</h4>
        <h6 class="font-weight-light">{{ subtitle }}</h6>
      </div>
    </div>
    <hr class="my-0" />
    <form @submit.prevent="$emit('onsub')" autocomplete="off" class="pt-3">
      <slot></slot>
      <div class="alert alert-danger" v-if="error">{{ error }}</div>
      <div class="text-center">
        <m-submit :load="load" class="btn-primary btn-lg">{{ btn }} </m-submit>
        <hr />
        <slot name="foot"></slot>
      </div>
    </form>
  </div>
</template>
<script>
export default {
  props: {
    title: String,
    subtitle: String,
    error: String,
    load: Boolean,
    btn: String
  }
};
</script>
