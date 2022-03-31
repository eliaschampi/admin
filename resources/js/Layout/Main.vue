<template>
  <div class="container-scroller d-flex">
    <app-sidebar></app-sidebar>
    <div class="container-fluid page-body-wrapper">
      <app-header></app-header>
      <main class="main-panel">
        <div id="overlay"></div>
        <div class="content-wrapper">
          <breadcrumb class="d-block d-md-none"></breadcrumb>
          <transition name="fade">
            <router-view></router-view>
          </transition>
        </div>
        <app-footer></app-footer>
      </main>
    </div>
    <modal
      :title="placeholder"
      id="finderModal"
      :withForm="false"
      btnName="Seleccionar"
      :disabled="!person"
      @save="afterSelected"
      @closed="handleFinderClosed"
    >
      <finder @input="handleItemChange" />
    </modal>
    <refresh />
  </div>
</template>

<script>
import AppHeader from "./partial/Header";
import AppSidebar from "./partial/Sidebar";
import AppFooter from "./partial/Footer";
import breadcrumb from "../Components/Lab/Breadcrumb";
import Finder from "../Components/Finder/Finder";
import { EventBus } from "../Helpers/bus";
import cache from "../Helpers/cache";
import { findlabel } from "../Mixins";
import Refresh from "../Views/Auth/Refresh.vue";
import { mapState } from "vuex";
export default {
  mixins: [findlabel],
  components: {
    AppHeader,
    AppSidebar,
    AppFooter,
    breadcrumb,
    Finder,
    Refresh
  },
  data() {
    return {
      person: null
    };
  },
  created() {
    EventBus.$on("showFinderModal", () => {
      $("#finderModal").modal("show");
    });
    this.$store.commit("user/FETCH_USER", cache.getItem("user"));
  },
  computed: {
    ...mapState({
      who: (state) => state.sfm_ops.who,
      mode: (state) => state.sfm_ops.mode
    }),
    studentDoesNotBelogHere() {
      if (this.who === "student") {
        return (
          this.person.branch_code !== this.$store.getters["user/branch_code"]
        );
      }
      return false;
    }
  },
  mounted() {
    window.addEventListener("scroll", this.handleNavbarEvent);
  },
  methods: {
    handleFinderClosed() {
      if (this.who === "student") {
        EventBus.$emit("sfm_has_closed");
      }
    },
    handleNavbarEvent() {
      const navbar = document.querySelector(".navbar");
      if (window.matchMedia("(min-width: 991px)").matches) {
        if (window.scrollY >= 197) {
          navbar.classList.add("navbar-mini");
          navbar.classList.add("fixed-top");
          document.body.classList.add("navbar-fixed-top");
        } else {
          navbar.classList.remove("navbar-mini");
          navbar.classList.remove("fixed-top");
          document.body.classList.remove("navbar-fixed-top");
        }
      }
      if (window.matchMedia("(max-width: 991px)").matches) {
        navbar.classList.add("navbar-mini");
        navbar.classList.add("fixed-top");
        document.body.classList.add("navbar-fixed-top");
      }
    },
    handleItemChange(value) {
      this.person = value;
    },
    afterSelected() {
      const { who, person } = this;
      if (this.mode === 1 && person.dni !== this.$route.params.dni) {
        if (this.studentDoesNotBelogHere) {
          this.$snack.show("El estudiante no pertenece a esta sede");
          return;
        }
        $("#finderModal").modal("hide");
        this.$store.commit(`${who}/FETCH_${who.toUpperCase()}`, person);
        setTimeout(() => {
          this.$router.push({
            name: `${who}_profile`,
            params: {
              dni: person.dni
            }
          });
        }, 500);
      } else {
        //2
        EventBus.$emit("afterSelectPerson", person, who);
      }
    }
  },
  beforeDestroy() {
    document.body.removeEventListener("scroll", this.handleNavbarEvent);
  }
};
</script>
