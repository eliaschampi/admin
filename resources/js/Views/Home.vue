<template>
  <section>
    <alert :title="username" type="alert-warning">
      <b>¡Bienvenido a {{ $store.getters["myapp"] }}</b> Descubre la nueva
      versión con grandes e importantes cambios de seguridad, rendimiento,
      velocidad y una nueva interfaz de usuario.
    </alert>
    <div class="mygrid gcard mt-4">
      <div class="px-4 py-1 bg-white shadow-sm rounded-md">
        <div>
          <div class="font-weight-medium">Estudiantes</div>
          <h2>{{ counts.s_count }}</h2>
        </div>
        <i class="icon ion-ios-contacts text-warning"></i>
      </div>
      <div class="px-4 py-1 bg-white shadow-sm rounded-md">
        <div>
          <div class="font-weight-medium">Docentes</div>
          <h2>{{ counts.t_count }}</h2>
        </div>
        <i class="icon ion-ios-person text-primary"></i>
      </div>
      <div class="px-4 py-1 bg-white shadow-sm rounded-md">
        <div>
          <div class="font-weight-medium">
            Matriculas {{ $store.getters["fullyear"] }}
          </div>
          <h2>{{ counts.r_count }}</h2>
        </div>
        <i class="icon ion-md-stats text-success"></i>
      </div>
      <div class="px-4 py-2 bg-white shadow-sm rounded-md">
        <div>
          <div class="font-weight-medium">Apoderados</div>
          <h2>{{ counts.f_count }}</h2>
        </div>
        <i class="icon ion-ios-people text-secondary"></i>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="bg-white p-4 rounded-md shadow-md mb-4 d-flex flex-wrap">
          <div v-if="rol.code === 'A'">
            <m-button
              color="btn-inverse-dark"
              size="btn-sm"
              @pum="handleReportTypeChange"
            >
              Ver
              {{ show_type === "admin" ? "academicos" : "administrativos" }}
            </m-button>
          </div>
          <m-button
            v-show="show_type === 'psico'"
            class="ml-auto"
            color="btn-inverse-primary"
            size="btn-sm"
            @pum="fetchAttChartData"
          >
            Actualizar gráficos
          </m-button>
        </div>

        <template v-if="loading">
          <pulse-loader class="text-center" />
        </template>
        <template v-else>
          <template v-if="show_type === 'admin'">
            <div class="bg-white p-4 rounded-md shadow-md">
              <h5 class="text-primary font-weight-medium">
                Gráfico acumulativo de ingresos rendidos
              </h5>
              <apexchart
                type="bar"
                height="300"
                :options="achar1"
                :series="serie_1"
              ></apexchart>
            </div>
            <div class="bg-white p-4 rounded-md shadow-md mt-4">
              <h5 class="text-primary font-weight-medium">
                Gráfico histórico de ingresos rendidos
              </h5>
              <apexchart
                type="area"
                height="300"
                :options="achar2"
                :series="serie_2"
              ></apexchart>
            </div>
          </template>
          <template v-else>
            <div class="bg-white p-4 rounded-md shadow-md">
              <h5 class="text-primary font-weight-medium">
                Gráfico de asistencia diaria
              </h5>
              <small>
                A partir del segundo trimestre anual, el grafico mostrará
                información solo de los tres últimos meses
              </small>
              <apexchart
                type="area"
                height="350"
                :options="dchart"
                :series="serie_3"
              ></apexchart>
            </div>

            <div class="bg-white p-4 rounded-md shadow-md my-4">
              <h5 class="text-primary font-weight-medium">
                Top 7 docentes con mas interacción
              </h5>
              <apexchart
                type="bar"
                height="350"
                :options="rchart"
                :series="serie_4"
              ></apexchart>
            </div>
          </template>
        </template>
      </div>
      <div class="col-md-6">
        <div class="bg-white p-4 rounded-md shadow-md">
          <h5 class="text-primary font-weight-medium">
            Area de {{ rol.name }}
          </h5>
          <alert> <b>Puedes realizar:</b> {{ rol.description }} </alert>
          <m-router
            :to="buttons[rol.code].b1.route"
            color="btn-inverse-warning"
            size="btn-sm"
          >
            {{ buttons[rol.code].b1.name }}
          </m-router>
          <m-router
            :to="buttons[rol.code].b2.route"
            color="btn-inverse-success"
            size="btn-sm"
          >
            {{ buttons[rol.code].b2.name }}
          </m-router>
          <m-router
            :to="buttons[rol.code].b3.route"
            color="btn-inverse-secondary"
            size="btn-sm"
          >
            {{ buttons[rol.code].b3.name }}
          </m-router>
        </div>
        <div class="bg-white p-4 rounded-md shadow-md mt-4">
          <h5 class="text-primary font-weight-medium">
            Envía un mensaje al desarrollador
          </h5>
          <alert type="alert-warning">
            Puedes enviar reportes de error, sugerencias o cosultas.
          </alert>
          <form @submit.prevent="sendMessage">
            <m-textarea id="Mensaje" v-model="message">
              Escribe aquí tu mensaje
              <small>{{ 380 - message.length }} restantes</small>
            </m-textarea>
            <div class="form-group">
              <m-submit
                :disabled="message.length < 10 || message.length > 380"
                color="btn-success"
                icon="icon ion-md-send"
              >
                Enviar
              </m-submit>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import VueApexCharts from "vue-apexcharts";
import api from "../Api/cash";
import attapi from "../Api/attendance";
import mainApi from "../Api/main";
import cache from "../Helpers/cache";
import { mapState } from "vuex";
export default {
  name: "Home",
  components: { apexchart: VueApexCharts },
  data() {
    return {
      serie_1: [],
      serie_2: [],
      serie_3: [],
      serie_4: [],
      counts: {},
      show_type: "admin",
      message: "",
      loading: true,
      achar1: {
        chart: {
          id: "myelias",
          type: "bar",
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#FEB019"],
        stroke: {
          width: [2],
          curve: "smooth"
        },
        xaxis: {
          categories: []
        },
        plotOptions: {
          bar: {
            columnWidth: "40%",
            horizontal: false
          }
        },
        fill: {
          opacity: [0.4]
        }
      },
      achar2: {
        chart: {
          type: "area",
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#38E5A4"],
        stroke: {
          width: [4],
          curve: "smooth"
        },
        yaxis: {
          labels: {
            formatter(value) {
              return `S/: ${Number(value).toFixed(2)}`;
            }
          }
        },
        xaxis: {
          categories: [],
          type: "category"
        }
      },
      dchart: {
        chart: {
          id: "elias",
          height: 350,
          type: "area"
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        xaxis: {
          categories: [],
          type: "datetime"
        },
        tooltip: {
          x: {
            format: "dd MMM"
          }
        }
      },
      rchart: {
        chart: {
          id: "elias",
          type: "bar",
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#269FFB", "#FEBB3B"],
        stroke: {
          width: [2, 1.5]
        },
        xaxis: {
          categories: [],
          type: "category"
        },
        tooltip: {
          shared: true,
          intersect: false
        },
        plotOptions: {
          bar: {
            columnWidth: "40%",
            horizontal: true
          }
        },
        fill: {
          opacity: [0.4, 0.5]
        }
      },
      buttons: {
        A: {
          b1: {
            name: "Niveles académicos",
            route: "/niveles_academicos"
          },
          b2: {
            name: "Ver docentes",
            route: "/docentes"
          },
          b3: {
            name: "Resumen anual",
            route: "/resumen"
          }
        },
        S: {
          b1: {
            name: "Agregar ingreso",
            route: "/ingreso/create"
          },
          b2: {
            name: "Agregar gasto",
            route: "/expense/create"
          },
          b3: {
            name: "Caja",
            route: "/caja"
          }
        },
        P: {
          b1: {
            name: "Crear atención",
            route: "/atenciones/create"
          },
          b2: {
            name: "Buscar apoderado",
            route: "/entity/apoderado"
          },
          b3: {
            name: "Asistencia",
            route: "/grado/asistencia_por_seccion"
          }
        },
        N: {
          b1: {
            name: "Crear Incidencia",
            route: "/incidencias/create"
          },
          b2: {
            name: "Resumen anual",
            route: "/resumen"
          },
          b3: {
            name: "Ver estudiante",
            route: "/entity/estudiante/perfil"
          }
        }
      }
    };
  },
  mounted() {
    if (["A", "S"].indexOf(this.rol.code) !== -1) {
      this.show_type = "admin";
      const ismobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
      if (ismobile === false) {
        this.achar1.plotOptions.bar.horizontal = true;
        this.achar1.xaxis.labels = {
          formatter(value) {
            return `S/: ${Number(value).toFixed(2)}`;
          }
        };
      }
      this.fetchChartData();
    } else {
      this.fetchOrRecalculateAttData();
    }

    if (cache.hasThis("counts")) {
      this.counts = cache.getItem("counts");
    } else {
      this.fetchCounts();
    }
  },
  computed: {
    ...mapState("user", {
      username: (state) => state.user.name,
      rol: (state) => state.user.rol
    })
  },
  methods: {
    sendMessage() {
      const username = this.$store.getters["user/fullname"];
      mainApi
        .sendToSupport({
          dni: this.$store.state.user.user.dni,
          content: `user ${username} message: ${this.message}`
        })
        .then((r) => {
          this.$snack.success(r.data);
          this.message = "";
        });
    },
    async fetchCounts() {
      const { data } = await mainApi.fetchCounts();
      this.counts = data;
      cache.setItem("counts", data);
    },
    async fetchChartData() {
      const { data } = await api.fetchChart();
      this.achar1.xaxis.categories = data.months;
      this.achar2.xaxis.categories = data.months;
      this.serie_1 = [
        {
          name: "Total acumulado",
          data: data.acum
        }
      ];
      this.serie_2 = [
        {
          name: "Total rendido del mes",
          data: data.surr
        }
      ];
      setTimeout(() => {
        this.loading = false;
      }, 400);
    },
    async fetchAttChartData() {
      const { data: data_a } = await attapi.fetchForChart();
      cache.setItem("data_att", {
        data_a,
        data_r: []
      });
      this.recalculateAttChart(data_a, []);
    },
    recalculateAttChart(data_a, data_r) {
      this.serie_4 = [
        { data: data_r.cms, name: "Comentarios" },
        { data: data_r.likes, name: "Likes" }
      ];
      this.rchart.xaxis.categories = data_r.teachers;
      this.serie_3 = [
        {
          name: "Estudiantes",
          data: data_a.count
        }
      ];
      this.dchart.xaxis.categories = data_a.days;
      setTimeout(() => {
        this.show_type = "psico";
        this.loading = false;
      }, 400);
    },
    fetchOrRecalculateAttData() {
      if (cache.hasThis("data_att")) {
        const { data_a, data_r } = cache.getItem("data_att");
        this.recalculateAttChart(data_a, data_r);
      } else {
        this.fetchAttChartData();
      }
    },
    handleReportTypeChange() {
      if (this.show_type === "admin") {
        this.loading = true;
        this.fetchOrRecalculateAttData();
      } else {
        this.show_type = "admin";
      }
    }
  }
};
</script>
<style lang="scss">
.apexcharts-toolbar {
  z-index: 0;
}
.gcard {
  > div {
    display: flex;
    justify-content: space-between;
    align-items: center;
    h2 {
      color: rgb(95, 156, 224);
    }
    > i {
      font-size: 4rem;
    }
  }
}
</style>
