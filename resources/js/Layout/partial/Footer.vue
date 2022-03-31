<template>
  <footer class="footer">
    <div class="container-fluid text-center">
      <div>
        {{ `${iname} ${nowYear}` }}
        <router-link to="/sistema" class="text-primary font-weight-bold">
          {{ $store.getters["myapp"] }}
        </router-link>
        Desarrollado con <i class="icon ion-ios-heart text-danger"></i>
      </div>
    </div>
  </footer>
</template>
<script>
export default {
  data() {
    return {
      nowYear: new Date().getFullYear(),
      iname: window.Laravel.INS
    };
  },
  name: "AppFooter"
};
</script>
