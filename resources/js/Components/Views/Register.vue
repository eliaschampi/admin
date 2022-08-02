<template>
  <div>
    <hr />
    <div class="border-rounded p-3 mx-auto" v-if="register">
      <div class="font-weight-medium text-primary title">
        Matrícula {{ register.year }}
      </div>
      <div class="subtitle">
        <span class="font-weight-bold text-secondary">
          {{ register.section_code.substr(-2) }}
        </span>
        de <b class="text-warning">{{ register.level }}</b>
      </div>
      <div class="badge mt-2" :class="states[register.state].badge">
        {{ states[register.state].text }}
      </div>
      <div class="mt-2">
        <span class="text-primary"> Mes.</span>
        {{ register.monthly | currency }}
      </div>
      <div class="font-weight-medium pointer mt-2 text-muted" @click="showRegs">
        Ver todas las matriculas
      </div>
    </div>
    <empty v-else :title="ifNotHasRegister"></empty>
    <div class="button-group mt-3" v-if="register && showActions">
      <m-router
        v-show="update_btn"
        size="btn-sm"
        color="btn-inverse-success"
        :to="{
          name: 'register',
          params: { dni: register.student_dni }
        }"
      >
        Modificar matricula
      </m-router>
      <m-button
        v-show="['a'].indexOf(register.state) !== -1 && canAs"
        size="btn-sm"
        color="btn-inverse-warning"
        icon="icon ion-md-print"
        @pum="printCard"
      >
        Carnet
      </m-button>
      <m-button
        v-show="['i', 'p'].indexOf(register.state) !== -1 && canAs"
        size="btn-sm"
        color="btn-inverse-danger"
        icon="icon ion-md-trash"
        @pum="delR"
      >
        Anular
      </m-button>
    </div>
    <modal
      id="registers_list"
      :with-form="false"
      btn-name="Cerrar"
      title="Matrículas del estudiante"
      @save="handleCloseModal"
    >
      <m-table
        :columns="['Codígo', 'Nivel y grado', 'Mes', 'Estado']"
        :data="registers"
        :head="false"
        class="overflownone"
      >
        <template slot="data">
          <tr :key="item.code" v-for="item in registers">
            <td>{{ item.code }}</td>
            <td>{{ item.section_code | full }}</td>
            <td>{{ item.monthly | currency }}</td>
            <td>
              <span class="badge" :class="states[item.state].badge">
                {{ states[item.state].text }}
              </span>
            </td>
          </tr>
        </template>
      </m-table>
    </modal>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import { states } from "../../Mixins/index";
import Empty from "../Lab/Empty.vue";
import mainapi from "../../Api/main";
export default {
  name: "RegisterPane",
  components: { Empty },
  mixins: [states],
  props: {
    ifNotHasRegister: {
      type: String,
      default: "Aun no esta matriculado."
    },
    showActions: Boolean
  },
  data() {
    return {
      registers: []
    };
  },
  watch: {
    "$route.params.dni"() {
      this.registers = [];
    }
  },
  computed: {
    ...mapState("register", ["register"]),
    canAs() {
      return this.$store.getters["can"]("ANS");
    },
    update_btn() {
      return (
        this.$route.name !== "register" &&
        this.register.state !== "f" &&
        this.canAs
      );
    }
  },
  methods: {
    ...mapActions({
      del: "register/del",
      fetchRegisters: "register/fetchRegisters"
    }),
    handleCloseModal() {
      $("#registers_list").modal("hide");
    },
    showRegs() {
      $("#registers_list").modal("show");
      if (!this.registers.length) {
        const dni = this.$route.params.dni;
        this.fetchRegisters(dni).then((registers) => {
          this.registers = registers;
        });
      }
    },
    printCard() {
      mainapi.printCard(this.register.code, "student").then((r) => {
        this.$downl(
          r.data,
          `carnet ${this.$store.getters["student/fullname"]}`
        );
      });
    },
    delR() {
      this.$snack.show({
        text: "Se eliminará esta matricula",
        button: "confirmar",
        action: () => {
          this.del().then((message) => {
            this.$snack.success(message);
            this.$router.push({ name: "sumary" });
          });
        }
      });
    }
  }
};
</script>
