<template>
  <ol class="d-flex align-items-center" style="width: 100%">
    <div class="d-flex mr-2">
      <router-link to="/">
        <i class="ion ion-md-home icon-md"></i>
      </router-link>
      <a type="button" @click="$router.go(-1)" class="mx-2">
        <i class="ion ion-md-arrow-round-back text-accent icon-md"></i>
      </a>
    </div>
    <strong>{{ $route.meta.title }}</strong>
  </ol>
</template>
<script>
export default {
  name: "breadcrumb"
};
</script>
