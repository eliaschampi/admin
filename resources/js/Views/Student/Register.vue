<template>
  <section id="newRegister">
    <alert class="my-4" type="alert-warning" v-if="existsInCache">
      Querido Usuario. No puede continuar porque hay una matrícula en proceso.
      Para finalizar o cancelar dicha matricula
      <router-link to="/ingreso/create" class="alert-link text-primary">
        Click aqui
      </router-link>
    </alert>
    <form v-else @submit.prevent="save" class="space-b1 mt-4">
      <h5 class="text-primary">
        {{ mode + " Matrícula " + $store.getters["fullyear"] }}
      </h5>
      <m-check
        v-if="mode === 'Modificar'"
        id="advance"
        text="Cambiar de grado"
        v-model="advance"
      />
      <degree
        v-show="advance || mode !== 'Modificar'"
        @changed="fetchSections(code)"
      />
      <hr />
      <div class="row">
        <div class="form-group col-md-6">
          <label for="regsecid">Sección</label>
          <select
            class="form-control"
            id="regsecid"
            name="seccion"
            v-validate="'required'"
            v-model="register.section_code"
          >
            <option
              :key="item.code"
              v-for="item in sections"
              :value="item.code"
            >
              {{ item.name }}
            </option>
          </select>
          <span class="small text-danger" v-show="errors.has('seccion')">
            {{ errors.first("seccion") }}
          </span>
        </div>
        <m-input
          class="col-md-6"
          id="Mensualidad"
          type="number"
          label="Mensualidad"
          v-validate="'required|max_value:1000|min_value:0'"
          :error="errors.first('Mensualidad')"
          v-model="register.monthly"
        />
      </div>
      <div>
        <m-switch
          id="consV"
          text="Generar Constancia de Vacancia (Nuevo estudiante)."
          v-model="consV"
        />
        <m-switch
          id="state"
          text="Registrar estado como Activo"
          v-model="state"
        />
        <m-switch
          :dis="mode === 'Modificar'"
          id="invoice"
          text="Realizar también el pago de matricula"
          v-model="invoicing"
        />
      </div>
      <div class="text-center">
        <m-submit :load="load" color="btn-primary">
          {{ invoicing ? "Ir a Ingresos" : "Registrar" }}
        </m-submit>
      </div>
    </form>
    <detail v-if="invoicing" :is-new="false" @before="store" />
  </section>
</template>
<script>
import { mapActions, mapState, mapGetters } from "vuex";
import api from "@/Api/register";
import Degree from "@/Components/Views/Degree";
import Detail from "../Income/Detail";
import { EventBus } from "../../Helpers/bus";
import { hasDetailModal } from "../../Mixins/utils";
export default {
  components: { Degree, Detail },
  mixins: [hasDetailModal],
  data() {
    return {
      invoicing: false,
      consV: false,
      load: false,
      state: true,
      register: {
        monthly: "300.00"
      },
      existsInCache: false,
      mode: "Nueva", // R, N, U,
      advance: false
    };
  },
  created() {
    EventBus.$on("registerFetched", this.fetchData);
    const rg = this.$store.state.register.register;
    if (rg) {
      this.fetchData(rg);
    }
  },
  mounted() {
    this.checkIfHasCache();
  },
  computed: {
    ...mapState("section", ["sections"]),
    ...mapGetters("student", ["dni", "fullname"]),
    ...mapGetters("degree", ["code"])
  },
  methods: {
    ...mapActions({
      fetchDegree: "degree/fetch",
      fetchSections: "section/fetchAll"
    }),

    async fetchData(regstated) {
      this.consV = false;
      const { section_code: sc, year } = regstated;
      try {
        if (parseInt(year) === new Date().getFullYear()) {
          this.$store.commit("degree/SET_DEGREE", {});
          this.$store.commit("section/FETCH_SECTIONS", { sections: [] });

          await this.fetchDegree(sc.substr(0, 9));

          await this.fetchSections(this.code);

          this.register = regstated;

          if (regstated.state === "p") {
            this.invoicing = true;
            this.mode = "Ratificar";
          } else {
            this.invoicing = false;
            this.mode = "Modificar";
          }
        } else {
          this.register = {};
          this.mode = "Nueva";
        }
      } catch (errormessage) {
        this.$snack.warning(errormessage);
        this.$router.push({ name: "home" });
      }
    },
    save() {
      if (this.invoicing) {
        let type = {
          code: "100",
          name: "Matricula"
        };
        if (!this.consV) {
          type = {
            code: "300",
            name: "Matrícula Ratificada"
          };
        }
        this.$store.commit("SET_DETAIL", {
          detail: {
            actiontype: type,
            topay: this.register.monthly,
            paid: this.register.monthly,
            month: ""
          }
        });
        this.showDetailModal();
      } else {
        this.store();
      }
    },
    checkIfHasCache() {
      api.hasOnCache().then(({ data }) => {
        this.existsInCache = data;
      });
    },
    onStoredSuccefully(datafromapi) {
      if (this.consV) {
        this.$downl(datafromapi, `cv_${this.fullname}`);
      } else {
        this.$snack.success(datafromapi);
      }
    },
    store() {
      this.$validator.validateAll().then((validated) => {
        if (validated) {
          this.load = true;

          this.register.state = this.state ? "a" : "i";
          this.register.mode = this.mode;
          this.register.consV = this.consV;
          this.register.student_name = this.fullname;

          if (this.mode === "Modificar") {
            this.register.invoicing = false;
          } else {
            if (this.mode === "Nueva") {
              this.register.student_dni = this.dni;
            }
            this.register.invoicing = this.invoicing;
          }

          return api
            .set(this.register)
            .then(({ data }) => {
              this.onStoredSuccefully(data);

              if (this.invoicing) {
                this.$router.push({
                  name: "invoice"
                });
              } else {
                this.$router.push({
                  name: "section_student",
                  params: {
                    degree_code: this.code
                  }
                });
              }
            })
            .catch((error) => {
              const { status, data } = error.response;
              if (status === 422) {
                if (this.$defined(data.errors)) {
                  this.$snack.danger(data.errors.student_dni[0]);
                }
              }
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    }
  },
  beforeDestroy() {
    EventBus.$off("registerFetched", this.fetchData);
  }
};
</script>
