<template>
  <panel
    :title="title"
    class="flex-fill mb-2"
    :f="true"
    style="max-width: 18rem"
  >
    <h2 class="font-weight-light text-dark">
      <i :class="icon"></i>
      {{ content | currency }}
    </h2>
    <div>
      <span class="text-accent">N° de {{ title }}:</span>
      {{ count }}
    </div>
    <template slot="foot">
      <m-router
        v-show="isToday"
        size="btn-sm"
        color="btn-primary"
        :to="{ name: gooAdd }"
      >
        Agregar
      </m-router>
      <m-button @pum="$emit('golist')" color="btn-light" size="btn-sm">
        Ver {{ title }}
      </m-button>
    </template>
  </panel>
</template>
<script>
export default {
  props: {
    title: String,
    icon: String,
    content: String,
    isToday: Boolean,
    count: Number,
    gooAdd: String
  }
};
</script>
