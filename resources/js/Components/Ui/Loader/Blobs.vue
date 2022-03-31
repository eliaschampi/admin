<template>
  <svg
    class="spinner spinner--circle"
    v-bind:style="styles"
    viewBox="0 0 66 66"
    xmlns="http://www.w3.org/2000/svg"
  >
    <circle
      class="path"
      cx="33"
      cy="33"
      fill="none"
      r="30"
      stroke-linecap="round"
      stroke-width="6"
    />
  </svg>
</template>
<script>
export default {
  props: {
    size: {
      default: "4.5em"
    }
  },
  computed: {
    styles() {
      return {
        width: this.size,
        height: this.size
      };
    }
  }
};
</script>
<style lang="scss" scoped>
$offset: 187;
$duration: 1.4s;
.spinner {
  animation: circle-rotator $duration linear infinite;

  * {
    line-height: 0;
    box-sizing: border-box;
  }
}

@keyframes circle-rotator {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(270deg);
  }
}

.path {
  stroke-dasharray: $offset;
  stroke-dashoffset: 0;
  transform-origin: center;
  animation: circle-dash $duration ease-in-out infinite,
    circle-colors ($duration * 4) ease-in-out infinite;
}

@keyframes circle-colors {
  0% {
    stroke: #2f4052;
  }
  25% {
    stroke: #de3e35;
  }
  50% {
    stroke: #f7c223;
  }
  75% {
    stroke: #00c951;
  }
  100% {
    stroke: #2e3e50;
  }
}

@keyframes circle-dash {
  0% {
    stroke-dashoffset: $offset;
  }
  50% {
    stroke-dashoffset: calc($offset / 4);
    transform: rotate(135deg);
  }
  100% {
    stroke-dashoffset: $offset;
    transform: rotate(450deg);
  }
}
</style>
