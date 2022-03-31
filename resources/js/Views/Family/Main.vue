<template>
  <main-wrapper ptype="family" @dw_fu="print">
    <template slot="profile-info">
      <span class="badge badge-secondary">Apoderado</span>
    </template>
    <work slot="room" />
    <panel title="Información del apoderado">
      <info route="new_f" :person="family">
        <m-plain label="Teléfono:" :value="self.telephone" />
        <m-plain label="Profesión:" :value="self.profession" />
        <m-plain label="Nivel de estudios:" :value="self.degree" />
        <m-plain label="Colegio:" :value="self.institute" />
      </info>
    </panel>
    <student-list />
  </main-wrapper>
</template>
<script>
import MainWrapper from "../Person/components/MainWrapper.vue";
import { mapState } from "vuex";
import Work from "./Work.vue";
import Info from "../Person/Info.vue";
import StudentList from "./StudentList.vue";
import { fetchData } from "../../Mixins";
import api from "../../Api/family";
export default {
  name: "FamilyMainLayout",
  mixins: [fetchData],
  components: {
    MainWrapper,
    Work,
    Info,
    StudentList
  },
  data() {
    return {
      self: {},
      loadP: false
    };
  },
  computed: {
    ...mapState("family", ["family"])
  },
  methods: {
    fetchData() {
      api.fetch(this.family.dni).then(({ data }) => {
        this.self = data.value;
      });
    },
    print() {
      if (this.loadP) return;
      this.loadP = true;
      api
        .printInfo(this.family.dni)
        .then(({ data }) => {
          this.$downl(
            data,
            `Apoderado ${this.$store.getters["family/fullname"]}`,
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
