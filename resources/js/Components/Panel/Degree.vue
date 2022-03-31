<template>
  <div id="admin">
    <panel class="mb-5 left-panel" style="max-width: 30rem">
      <degree @changed="onChanged" />
    </panel>
    <div class="row" v-if="exists">
      <div class="col-md-12" v-if="allowed">
        <transition name="fade" mode="out-in">
          <router-view></router-view>
        </transition>
      </div>
      <div class="col-md-12 text-center" v-else>
        <p>Este grado aun no tiene secciones, tiene las siguientes opciones.</p>
        <m-router
          :to="{
            name: 'migrate_student',
            params: { degree_code: code }
          }"
        >
          Migrar del año anterior
        </m-router>
        <m-router
          color="btn-light"
          :to="{ name: 'new_section', params: { degree_code: code } }"
        >
          Registrar nueva Seccion
        </m-router>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Degree from "../Views/Degree";
export default {
  components: { Degree },
  created() {
    const r_code = this.$route.params.degree_code;
    if (this.$defined(r_code)) {
      if (!this.exists || this.code !== r_code) {
        this.fetch(r_code).catch((errormessage) => {
          this.$snack.warning(errormessage);
          this.$router.push({ name: this.routeName });
        });
      }
    }
  },
  computed: {
    ...mapGetters("degree", ["sections_count", "exists", "code"]),
    routeName() {
      return this.$route.name;
    },
    allowed() {
      const alloweds = ["new_section", "migrate_student"];
      return this.sections_count > 0 || alloweds.includes(this.routeName);
    }
  },
  methods: {
    ...mapActions("degree", ["fetch"]),
    onChanged() {
      this.$router.push({
        name: this.routeName,
        params: {
          degree_code: this.code
        }
      });
    }
  }
};
</script>
