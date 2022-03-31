<template>
  <div class="error-box text-center">
    <h1 class="display-1 font-weight-bold text-primary">
      <i class="icon ion-md-partly-sunny text-warning"></i> 404
    </h1>
    <h2 class="font-weight-light my-3">
      {{ title }}
      <br />
      <small>{{ subtitle }}</small>
    </h2>
    <m-button color="btn-success" @pum="goo">Estar a salvo</m-button>
  </div>
</template>
<script>
export default {
  data() {
    return {
      title: "¡Vaya! Tu solicitud no ha sido encontrado en el servidor",
      subtitle: "Es posible no que no exista o que haya sido borrado"
    };
  },
  methods: {
    goo() {
      window.history.go(-2);
    }
  }
};
</script>
