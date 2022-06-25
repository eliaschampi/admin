<template>
  <card :title="'Reporte acádemico del Año ' + fullyear">
    <m-button
      slot="rb"
      color="btn-inverse-accent"
      class="btn-icon"
      @pum="fetchSumary"
      icon="icon ion-md-refresh"
    >
    </m-button>
    <alert type="alert-primary">
      Las migraciones del grado anterior ya estan disponibles. Los estudiantes
      migrados al nuevo año tienen el estado <b>PENDIENTE</b>
      y ahora estan disponibles en cada sección.
      <br />
      <router-link
        v-can="'A'"
        :to="{
          name: 'migrate_student',
          params: { degree_code: dc }
        }"
      >
        Migrar del año anterior
      </router-link>
    </alert>
    <div class="button-group" v-can="'A'">
      <m-router
        size="btn-sm"
        color="btn-inverse-success"
        :to="{
          name: 'new_section',
          params: { degree_code: dc }
        }"
      >
        Crear Sección
      </m-router>
      <m-router
        size="btn-sm"
        color="btn-inverse-warning"
        :to="{
          name: 'migrate_student',
          params: { degree_code: dc }
        }"
      >
        Migrar del año anterior
      </m-router>
    </div>
    <div class="selectgroup selectgroup-pills mt-3">
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value=""
          class="selectgroup-input"
          v-model="buscado"
        />
        <span class="selectgroup-button">Todos</span>
      </label>
      <template v-for="cy in cycles">
        <label class="selectgroup-item" :key="cy.code">
          <input
            type="radio"
            name="mode"
            :value="cy.type"
            class="selectgroup-input"
            v-model="buscado"
          />
          <span class="selectgroup-button">{{ cy.full_name }}</span>
        </label>
      </template>
    </div>
    <m-table
      :columns="columns"
      :data="filtered"
      :head="false"
      v-model="buscado"
    >
      <template slot="data">
        <tr v-for="item in filtered" :key="item.code">
          <td>
            <b>{{ item.code | level }}</b>
          </td>
          <td>{{ item.degree.full_name }}</td>
          <td>
            <b>{{ item.name }}</b>
          </td>
          <td>
            <b class="badge badge-warning p-2">
              {{ item.registers_count }}
            </b>
          </td>
          <td>
            <b
              class="text-primary pointer"
              @click="handleChangeTutor(item.code)"
            >
              <template v-if="item.tutor">
                {{ `${item.tutor.person.name} ${item.tutor.person.lastname}` }}
              </template>
              <template v-else> Establecer </template>
            </b>
          </td>
          <td>
            <m-button
              @pum="onItem(item, 'section_student')"
              size="btn-sm"
              color="btn-inverse-primary"
            >
              Estudiantes
            </m-button>
            <m-button
              @pum="onItem(item, 'schedule')"
              size="btn-sm"
              color="btn-inverse-accent"
              v-can="'NS'"
            >
              Horario
            </m-button>
          </td>
        </tr>
        <tr>
          <th colspan="5" class="text-right text-accent">
            Total de Estudiantes:
          </th>
          <th>{{ total }}</th>
        </tr>
      </template>
    </m-table>
  </card>
</template>
<script>
import cycleApi from "@/Api/cycle";
import { mapState, mapActions, mapGetters } from "vuex";
import Alert from "@/Components/Ui/Alert.vue";
import { EventBus } from "@/Helpers/bus";
export default {
  components: { Alert },
  data() {
    return {
      columns: [
        "Nivel",
        "Grado",
        "Sección",
        "N° Estudiantes",
        "Tutor",
        "Acciones"
      ],
      buscado: "",
      cycles: [],
      selected: null
    };
  },
  created() {
    EventBus.$on("afterSelectPerson", this.afterSelectPerson);
  },
  mounted() {
    if (this.$route.params.level) {
      this.buscado = this.$route.params.level;
    }
    this.fetchCycles(this.fetchSumary);
  },
  computed: {
    ...mapState("section", ["sumaries"]),
    ...mapGetters(["fullyear"]),
    filtered() {
      if (this.buscado) {
        return this.sumaries.filter(
          (item) => item.code.substr(4, 3) === this.buscado
        );
      }
      return this.sumaries;
    },
    total() {
      return this.filtered.reduce((acu, item) => acu + item.registers_count, 0);
    },
    dc() {
      return this.$store.state.degree.degree.code;
    }
  },
  methods: {
    ...mapActions("section", ["fetchSumary", "changeTutor"]),
    afterSelectPerson({ dni, name, lastname }) {
      this.changeTutor({
        person: { dni, name, lastname },
        section_code: this.selected
      }).then((message) => {
        this.$snack.success(message);
        this.selected = null;
        $("#finderModal").modal("hide");
      });
    },
    handleChangeTutor(section_code) {
      this.$store.dispatch("updateSfmOption", {
        who: "teacher",
        mode: 2,
        after: () => {
          EventBus.$emit("showFinderModal");
          this.selected = section_code;
        }
      });
    },
    async fetchCycles(fetchData) {
      const { data } = await cycleApi.fetchAll();
      this.cycles = data.values;
      fetchData();
    },
    onItem(item, where) {
      this.$store.commit("section/SET_SECTION_CODE", item.code);
      this.$router.push({
        name: where,
        params: {
          degree_code: item.degree_code
        }
      });
    }
  },
  beforeDestroy() {
    EventBus.$off("afterSelectPerson", this.afterSelectPerson);
  }
};
</script>
