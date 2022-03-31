<template>
  <div style="max-width: 40rem">
    <panel title="Año acádemico">
      <alert>
        La información academica y administrativa del sistema será gestionado
        solamente del año establecido
      </alert>
      <div class="yearange">
        <input
          name="switch"
          id="one"
          type="radio"
          value="2019"
          v-model="currentYear"
        />
        <label for="one" class="yearange__label">2019</label>
        <input
          name="switch"
          id="two"
          type="radio"
          value="2020"
          v-model="currentYear"
        />
        <label for="two" class="yearange__label">2020</label>
        <input
          name="switch"
          id="three"
          type="radio"
          value="2021"
          v-model="currentYear"
        />
        <label for="three" class="yearange__label">2021</label>
        <input
          name="switch"
          id="four"
          type="radio"
          value="2022"
          v-model="currentYear"
        />
        <label for="four" class="yearange__label">2022</label>
        <div class="yearange__indicator">
          <span>{{ currentYear }}</span>
        </div>
      </div>
      <m-button
        :disabled="disableYearBtn"
        color="btn-success"
        @pum="handleUpdateYear"
      >
        Guardar
      </m-button>
    </panel>
    <panel title="Configuración del sistema" class="mt-4">
      <div class="item mb-4">
        <div class="text-warning">Nombre del Año</div>
        <input
          class="form-control"
          disabled
          :value="config.YNE"
          style="max-width: 15rem"
          type="text"
        />
      </div>
      <div class="item mb-4">
        <div class="text-warning">Paginación de registros</div>
        <span class="text-small text-muted mb-2">
          {{ config.pgnt }} registros por página
        </span>
        <input
          class="form-control"
          type="range"
          style="max-width: 15rem"
          @blur="handleSubmit($event.target.value, 'pgnt')"
          v-model="config.pgnt"
          min="10"
          max="50"
        />
      </div>
      <div class="item mb-4">
        <m-switch
          id="hfwerf"
          text="Habilitar registro programado de inasistencias"
          :checked="st_att_left"
          @change="handleStSub"
        />
      </div>
      <div class="item mb-4">
        <div class="text-warning">
          Asistencia general: <b>{{ config.absence }} minutos</b>
        </div>
        <span class="text-small mb-2">
          Se cuenta a partir del tiempo de tolerancia. Durante este tiempo la
          asistencia se registra como tarde. Pasado este tiempo si el registro
          programado esta habilitado se registra automaticamente como falta
        </span>
        <input
          class="form-control"
          type="range"
          style="max-width: 15rem"
          @blur="handleSubmit($event.target.value, 'absence')"
          v-model="config.absence"
          min="10"
          max="60"
        />
      </div>
      <div class="item">
        <b class="text-warning">Horario de ingreso para docentes:</b>
        {{ entry_time }}
      </div>
      <div class="item">
        <b class="text-warning">Tolerancia de asistencia:</b>
        {{ tolerance }} minutos
      </div>
      <hr />
      <div class="item mb-4">
        <div class="text-warning">
          Fecha de cancelación de matrículas no ratificadas
        </div>
        <span class="text-small text-muted mb-2">
          En esta fecha todas la matrículas pendientes serán eliminados
        </span>
        <input
          class="form-control"
          style="max-width: 15rem"
          @blur="handleSubmit($event.target.value, 'cancelate')"
          type="date"
          v-model="config.cancelate"
        />
      </div>
      <div class="alert alert-warning">
        Clave de Seguridad:
        <b>{{ main_key }}</b>
      </div>
    </panel>
  </div>
</template>
<script>
import api from "@/Api/main";
import userapi from "@/Api/user";
export default {
  data() {
    return {
      st_att_left: false,
      currentYear: new Date().getFullYear(),
      config: {
        YNE: "",
        pgnt: "",
        absence: "",
        cancelate: ""
      },
      main_key: window.Laravel.secret_key,
      entry_time: window.Laravel.entry_time,
      tolerance: window.Laravel.tolerance
    };
  },
  created() {
    this.fethData();
  },
  mounted() {
    this.currentYear = this.$store.getters["fullyear"];
  },
  computed: {
    disableYearBtn() {
      return parseInt(this.currentYear) === this.$store.getters["fullyear"];
    }
  },
  methods: {
    handleUpdateYear() {
      this.$snack.show({
        text: "Se modificará el año academico",
        button: "confirmar",
        action: () => {
          const current_year = parseInt(this.currentYear);
          userapi.changeCurrentYear(current_year).then((r) => {
            this.$store.dispatch("updateUserCachedProps", {
              current_year
            });
            this.$snack.success(r.data);
            this.$router.go(0);
          });
        }
      });
    },
    fethData() {
      api.fetchByTags("main_asis_mat").then((r) => {
        r.data.configs.forEach((item) => {
          if (item.code === "st_att_left") {
            this.st_att_left = item.value === "1";
          } else {
            this.config[item.code] = item.value;
          }
        });
      });
    },
    handleStSub(value) {
      this.handleSubmit(value ? "1" : "0", "st_att_left");
      this.st_att_left = value;
    },
    handleSubmit(value, code) {
      this.$snack.show({
        text: "¿Quieres guardar los cambios?",
        button: "continuar",
        action: () => {
          api
            .setConfig({
              code,
              value
            })
            .then((r) => {
              this.$snack.success(r.data);
            });
        }
      });
    }
  }
};
</script>
