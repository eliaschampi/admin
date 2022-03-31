<template functional>
  <div class="row" :class="props.wraped ? 'my-1' : 'space-b'">
    <div
      class="col-sm-12 font-weight-medium text-accent"
      :class="{ 'col-md-3': !props.wraped }"
    >
      {{ props.label }}
    </div>
    <div class="col-sm-12" :class="{ 'col-md-9': !props.wraped }">
      <slot>{{ props.value }}</slot>
    </div>
  </div>
</template>
