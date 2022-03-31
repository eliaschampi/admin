<template functional>
  <router-link
    :to="{
      name: props.route,
      params: { dni: props.person.dni }
    }"
    class="text-primary font-weight-medium"
  >
    {{ `${props.person.name} ${props.person.lastname}` }}
  </router-link>
</template>
