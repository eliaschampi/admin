<template>
  <li class="nav-item">
    <router-link
      class="nav-link"
      data-toggle="tab"
      :class="{ active: $route.name === route }"
      :to="{ name: route, params: { dni: dni } }"
      role="tab"
    >
      <i aria-hidden="true" class="icon" :class="icon" />
      <span class="ml-2 d-none d-md-block d-lg-block">
        {{ title }}
      </span>
    </router-link>
  </li>
</template>
<script>
export default {
  name: "NavItem",
  props: ["route", "title", "icon", "dni"]
};
</script>
