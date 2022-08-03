<template>
  <main-wrapper ptype="teacher">
    <div slot="profile-info" class="border-rounded p-3 mx-auto mt-3">
      <div class="font-weight-medium text-primary title">
        <template v-if="['ADM', 'MIX'].indexOf(teacher.specialty) === -1">
          Docente de
        </template>
        {{ specialties[teacher.specialty] }}
      </div>
      <p class="card-description my-2">
        Fecha de inicio: {{ teacher.startdate | date }}
      </p>
      <div>
        <div class="text-sm text-warning">
          ¿Activo en {{ new Date().getFullYear() }}?
        </div>
        <div class="switch mt-2">
          <input
            type="checkbox"
            :disabled="!$store.getters.can('AN')"
            id="tcstate"
            v-model="teacher.state"
            @input="handleChangeState"
          />
          <label for="tcstate" class="accent">my toggle</label>
        </div>
      </div>
    </div>
    <div class="p-4 bg-white shadow-md rounded-md">
      <ul class="nav nav-pills nav-pills-no-bd nav-pills-icons" role="tablist">
        <nav-item
          icon="ion-md-person"
          title="Información"
          route="teacher_profile"
          :dni="dni"
        />
        <nav-item
          icon="ion-ios-calendar"
          title="Horario"
          route="t_schedule"
          :dni="dni"
        />
        <nav-item
          v-show="attendanceShowable"
          icon="ion-ios-checkmark-circle-outline"
          title="Asistencia"
          route="t_attendance"
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
    <template slot="profile-foot">
      <m-button
        :disabled="$store.getters['can']('P') || loadP"
        color="btn-inverse-warning"
        @pum="printCard"
        size="btn-sm"
      >
        Imprimir carnet
      </m-button>
    </template>
  </main-wrapper>
</template>
<script>
import MainWrapper from "../Person/components/MainWrapper.vue";
import NavItem from "../../Components/Views/NavItem";
import { mapGetters } from "vuex";
import api from "../../Api/teacher";
import configApi from "../../Api/main";
export default {
  name: "TeacherMainLayout",
  components: {
    MainWrapper,
    NavItem
  },
  data() {
    return {
      loadP: false,
      teacher: {},
      specialties: []
    };
  },
  mounted() {
    this.fetchTeacher();
  },
  computed: {
    ...mapGetters("teacher", ["dni"]),
    attendanceShowable() {
      const { branch_code, rol_code } = this.$store.state.user.user;
      return this.teacher.branch_code === branch_code && rol_code !== "S";
    }
  },
  methods: {
    async fetchTeacher() {
      const { data: spes } = await configApi.fetchByTagsPlucked(
        "cy.m_doc_cs.d"
      );
      this.specialties = spes.configs;
      const { data } = await api.fetch(this.dni);
      this.teacher = data.value;
    },
    async handleChangeState() {
      const { data } = await api.changeState(this.dni);
      this.$snack.success(data.message);
    },
    printCard() {
      if (this.loadP) return;
      this.loadP = true;
      configApi
        .printInfo(this.dni)
        .then(({ data }) => {
          this.$downl(
            data,
            `Docente ${this.$store.getters["teacher/fullname"]}`,
            ".pdf"
          );
        })
        .finally(() => {
          this.loadP = false;
        });
    }
  }
};
</script>
