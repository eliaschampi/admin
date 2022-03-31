<template>
  <div class="error-body text-center text-muted">
    <h1 class="text-inverse">401</h1>
    <h3 class="text-uppercase font-weight-bold">{{ title }}</h3>
    <h3>{{ subtitle }}</h3>
    <m-button @pum="goo" class="mt-4">Porfavor vuelva atras</m-button>
  </div>
</template>
<script>
export default {
  data() {
    return {
      title: "¡Vaya! No tienes permiso para realizar esta operación",
      subtitle: "Es posible que tu rol no cumpla dicha habilidad"
    };
  },
  methods: {
    goo() {
      history.back();
    }
  }
};
</script>
