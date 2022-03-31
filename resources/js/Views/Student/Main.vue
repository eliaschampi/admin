<template>
  <main-wrapper ptype="student" @dw_fu="print">
    <register slot="profile-info" show-actions />
    <div class="p-4 bg-white shadow-md rounded-md">
      <ul class="nav nav-pills nav-pills-no-bd nav-pills-icons" role="tablist">
        <nav-item
          icon="ion-md-person"
          title="Información"
          route="student_profile"
          :dni="dni"
        />
        <nav-item
          icon="ion-md-people"
          title="Apoderados"
          route="es_family"
          :dni="dni"
        />
        <nav-item
          v-can="'S'"
          icon="ion-md-folder-open"
          title="Complementario"
          route="more_over"
          :dni="dni"
        />
      </ul>
      <div class="tab-content">
        <div
          class="tab-pane fade show active"
          :id="$route.name"
          role="tabpanel"
        >
          <transition name="fade" mode="out-in">
            <router-view></router-view>
          </transition>
        </div>
      </div>
    </div>
  </main-wrapper>
</template>
<script>
import Register from "../../Components/Views/Register";
import MainWrapper from "../Person/components/MainWrapper.vue";
import { mapActions, mapGetters } from "vuex";
import api from "../../Api/student";
import { EventBus } from "../../Helpers/bus";
import NavItem from "../../Components/Views/NavItem.vue";
export default {
  name: "StudentMainLayout",
  components: {
    Register,
    MainWrapper,
    NavItem
  },
  data() {
    return {
      loadP: false
    };
  },
  mounted() {
    this.fetchReg(this.$route.params.dni);
  },
  watch: {
    "$route.params.dni"(val) {
      this.fetchReg(val);
    }
  },
  computed: {
    ...mapGetters("student", ["dni"])
  },
  methods: {
    ...mapActions({
      changeImage: "person/changeImage",
      fetchRegister: "register/fetchRegister"
    }),
    fetchReg(dni) {
      this.fetchRegister({
        dni,
        states: "apfi",
        mod: "last_register"
      }).then((register) => {
        if (this.$route.name === "register" && register !== null) {
          EventBus.$emit("registerFetched", register);
        }
      });
    },
    print() {
      if (this.loadP) return;
      this.loadP = true;
      api
        .printInfo(this.dni)
        .then(({ data }) => {
          this.$downl(
            data,
            `Estudiante ${this.$store.getters["student/fullname"]}`,
            ".zip"
          );
        })
        .finally(() => {
          this.loadP = false;
        });
    }
  }
};
</script>
