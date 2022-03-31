<template>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div
            class="col-lg-6 d-flex align-items-center justify-content-center"
          >
            <router-view></router-view>
          </div>
          <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
            <p
              class="text-white font-weight-medium text-center flex-grow align-self-end"
            >
              Copyright &copy; Aeduca 2021 todos los derechos reservados.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "AuthLayout"
};
</script>
