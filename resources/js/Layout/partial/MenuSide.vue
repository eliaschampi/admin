<template>
  <li class="nav-item">
    <a
      class="nav-link"
      data-toggle="collapse"
      :href="`#${id}`"
      aria-expanded="false"
      :aria-controls="id"
    >
      <i class="menu-icon" :class="icon"></i>
      <span class="menu-title">{{ name }}</span>
      <i class="menu-arrow"></i>
    </a>
    <div class="collapse" :id="id">
      <ul class="nav flex-column sub-menu">
        <slot></slot>
      </ul>
    </div>
  </li>
</template>
<script>
export default {
  props: ["id", "icon", "name"]
};
</script>
