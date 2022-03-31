<template>
  <div class="text-center my-4">
    <img src="/img/empty.png" style="max-width: 200px" />
    <div class="text-muted mt-2">{{ title }}</div>
  </div>
</template>
<script>
export default {
  name: "Empty",
  props: {
    title: {
      type: String,
      default: "No hay nada para mostrar"
    }
  }
};
</script>
