<template>
  <div id="person_container">
    <panel :title="$route.meta.title" class="mb-5">
      <div class="form-inline">
        <input-finder
          :who="ptype"
          :only_current_reg="ptype !== 'student'"
          :mode="2"
        />
        <m-button
          color="btn-light"
          class="mt-2 ml-md-2 ml-mt-0"
          :load="loading"
          :disabled="!dni || loading"
          @pum="fetch"
        >
          {{ isMain ? "Actualizar" : "Ir a Perfil" }}
        </m-button>
      </div>
    </panel>
    <section id="info-container" v-if="dni">
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </section>
    <pulse-loader class="text-center" v-else-if="loading" />
    <div class="text-center" v-else>
      <div class="text-muted mb-4">
        No ha sido seleccionado ningún
        {{
          ptype === "student"
            ? "estudiante"
            : ptype === "teacher"
            ? "docente"
            : "apoderado"
        }}.
      </div>
      <m-router v-can="'AS'" :to="{ name: `new_${ptype.substr(0, 1)}` }">
        Registrar ahora
      </m-router>
    </div>
  </div>
</template>

<script>
import InputFinder from "@/Components/Finder/InputFinder.vue";
import { EventBus } from "@/Helpers/bus";
import personApi from "@/Api/person";
export default {
  name: "Container",
  components: { InputFinder },
  data() {
    return {
      loading: false
    };
  },
  created() {
    const { routedni, dni } = this;
    if (this.validatedni(routedni, dni)) {
      this.fetchData(this.$route.name, routedni);
    }
    EventBus.$on("afterSelectPerson", this.afterSelect);
  },
  watch: {
    routedni(val) {
      if (val !== this.dni) {
        this.fetchData(this.$route.name, val);
      }
    }
  },
  computed: {
    dni() {
      return this.$store.getters[`${this.ptype}/dni`];
    },
    routedni() {
      return this.$route.params.dni;
    },
    ptype() {
      const { ptype } = this.$route.meta;
      if (ptype) {
        return ptype;
      }
      return "student";
    },
    profileroute() {
      return `${this.ptype}_profile`;
    },
    isMain() {
      return this.$route.name === this.profileroute;
    }
  },
  methods: {
    validatedni(routedni, dni) {
      return !isNaN(routedni) && routedni.length === 8 && routedni !== dni;
    },
    afterSelect(person) {
      this.commitPerson(person);
      const { routedni, dni } = this;
      if (routedni !== dni) {
        this.$router.push({
          name: this.$route.name,
          params: {
            dni
          }
        });
      }
      $("#finderModal").modal("hide");
    },
    commitPerson(value) {
      const { ptype } = this;
      const _ptype = ptype.toUpperCase();
      this.$store.commit(`${ptype}/FETCH_${_ptype}`, value);
    },
    fetchData(errroute, dni, onSuccess = () => {}) {
      this.loading = true;
      personApi
        .fetch(this.ptype, dni)
        .then(({ data }) => {
          this.commitPerson(data.value);
          onSuccess();
        })
        .catch((err) => {
          this.commitPerson(null);
          if (err.code === 404) {
            this.$snack.warning("No se encontraron resultados en esta sede.");
            this.$router.push({ name: errroute });
          } else {
            this.$snack.warning("Ocurrió un error inesperado.");
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetch() {
      const { dni, profileroute, isMain } = this;
      this.fetchData(profileroute, dni, () => {
        if (!isMain) {
          this.$router.push({
            name: profileroute,
            params: {
              dni
            }
          });
        }
      });
    }
  },
  beforeDestroy() {
    EventBus.$off("afterSelectPerson", this.afterSelect);
  }
};
</script>
