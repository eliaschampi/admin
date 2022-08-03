<template>
  <nav
    class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row"
    :class="`bg${bcode}`"
  >
    <div
      class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
    >
      <button
        class="navbar-toggler navbar-toggler align-self-center"
        type="button"
        @click="handleSidebarMenuClick"
      >
        <span class="icon ion-md-menu"></span>
      </button>
      <div class="navbar-brand-wrapper">
        <router-link class="navbar-brand brand-logo" to="/">
          <img src="/img/logo.png" alt="logo" />
        </router-link>
        <router-link class="navbar-brand brand-logo-mini" to="/"
          ><img src="/img/carrion.svg" alt="logo" />
        </router-link>
      </div>
      <h5 class="font-weight-medium mb-0 mt-1">
        {{ title }}
      </h5>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item">
          <h5 class="mb-0 font-weight-medium d-none d-xl-block">
            {{ currentTime }}
          </h5>
        </li>
        <li class="nav-item dropdown mr-1">
          <a
            class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
            id="messageDropdown"
            href="javascript:void(0)"
            data-toggle="dropdown"
          >
            <i class="icon ion-md-search mx-0"></i>
          </a>
          <div
            class="dropdown-menu dropdown-menu-right navbar-dropdown"
            aria-labelledby="searchDropdown"
          >
            <button @click="showFinder('student')" class="dropdown-item">
              <i class="icon ion-md-person text-primary"></i>
              Buscar estudiante
            </button>
            <button @click="showFinder('family')" class="dropdown-item">
              <i class="icon ion-md-person text-primary"></i>
              Buscar apoderado
            </button>
            <button @click="showFinder('teacher')" class="dropdown-item">
              <i class="icon ion-md-person text-primary"></i>
              Buscar docente
            </button>
          </div>
        </li>
        <li class="nav-item dropdown mr-2">
          <router-link
            class="nav-link count-indicator d-flex align-items-center justify-content-center"
            id="cashitem"
            :to="{ name: 'cash' }"
            v-if="$store.getters['can']('AS')"
          >
            <i class="icon ion-md-cart text-warning mx-0"></i>
          </router-link>
        </li>
      </ul>
      <button
        class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
        type="button"
        data-toggle="offcanvas"
      >
        <span class="icon ion-md-menu"></span>
      </button>
    </div>
    <div
      class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center"
    >
      <ul class="navbar-nav mr-lg-2">
        <li class="nav-item">
          <ol class="d-flex align-items-center" style="width: 100%">
            <div class="d-flex mr-2 vl">
              <router-link to="/">
                <i class="icon ion-md-home icon-md"></i>
              </router-link>
              <div class="text-accent pointer mx-2" @click="$router.go(-1)">
                <i class="icon ion-md-arrow-round-back icon-md"></i>
              </div>
            </div>
            <h5 class="text-dark mb-0">
              {{ $route.meta.title }}
            </h5>
          </ol>
        </li>
      </ul>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item nav-profile dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="javascript:void(0)"
            data-toggle="dropdown"
            id="profileDropdown"
          >
            <img :src="$store.getters['user/image']" alt="profile" />
            <span class="nav-profile-name">{{ uname }}</span>
          </a>
          <div
            class="dropdown-menu dropdown-menu-right navbar-dropdown"
            aria-labelledby="profileDropdown"
          >
            <router-link :to="{ name: 'user_profile' }" class="dropdown-item">
              <i class="icon ion-md-person text-primary"></i>
              Mi Perfil
            </router-link>
            <a class="dropdown-item" @click="log">
              <i class="icon ion-ios-log-out text-primary"></i>
              Cerrar Sesión
            </a>
          </div>
        </li>
        <li class="nav-item">
          <router-link
            href="javascript:void(0)"
            :to="{ name: 'about' }"
            class="nav-link icon-link text-success"
          >
            <i class="icon ion-md-information-circle"></i>
          </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { EventBus } from "../../Helpers/bus";
import { mapState } from "vuex";
import { dformat } from "../../Helpers/day";
import { logout } from "../../Mixins";
export default {
  name: "AppHeader",
  mixins: [logout],
  data() {
    return {
      currentTime: null,
      timer: null
    };
  },
  mounted() {
    this.currentTime = dformat(new Date(), "dddd DD [de] MMMM h:mm A");
    this.timer = setInterval(() => this.updateCurrentTime(), 60000);
  },
  computed: {
    ...mapState("user", {
      uname: (state) => state.user.name,
      bcode: (state) => state.user.branch_code
    }),
    title() {
      const branch = this.$store.getters["user/branch"];
      const user_year = this.$store.getters["fullyear"];
      return `${branch} - ${user_year}`;
    }
  },
  methods: {
    updateCurrentTime() {
      this.currentTime = dformat(new Date(), "dddd DD [de] MMMM h:mm A");
    },
    showFinder(who) {
      this.$store.dispatch("updateSfmOption", {
        who,
        mode: 1,
        only_current_reg: false,
        only_current_branch: false,
        after: () => {
          EventBus.$emit("showFinderModal");
        }
      });
    },
    handleSidebarMenuClick() {
      if (document.body.classList.contains("sidebar-toggle-display")) {
        document.body.classList.toggle("sidebar-hidden");
      } else {
        document.body.classList.toggle("sidebar-icon-only");
      }
    }
  },
  beforeDestroy() {
    clearInterval(this.timer);
  }
};
</script>
