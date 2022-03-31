<template>
  <transition name="snack">
    <div
      class="snackbar"
      :class="[config.position, `snackbar-${theme}`]"
      v-if="active"
    >
      <div class="snackbar_icon">
        <span :class="icon"></span>
      </div>
      <div class="snackbar__text">{{ text }}</div>
      <template v-if="button">
        <div @click.prevent="action" class="snackbar__action">{{ button }}</div>
      </template>
      <template v-if="config.close">
        <div @click="$emit('close')" class="times">
          <span class="icon ion-md-close icon-md"></span>
        </div>
      </template>
    </div>
  </transition>
</template>

<script>
export default {
  name: "Snackbar",
  props: {
    config: Object
  },
  data: () => ({
    active: false,
    icon: "icon ion-ios-notifications icon-md",
    text: "",
    button: "",
    theme: "default",
    action: null
  })
};
</script>
