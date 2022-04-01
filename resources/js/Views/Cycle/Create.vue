<template>
  <modal
    title="Niveles Académicos"
    @sub="save"
    id="newCycle"
    btn-name="Finalizar"
    :disabled="!isOpenMode"
  >
    <template v-if="!isOpenMode">
      <div class="form-row myselect">
        <div class="form-group col-sm-12">
          <label for="cytypeselect">Seleccione una modalidad</label>
          <select class="form-control" id="cytypeselect" v-model="type">
            <option :key="item.code" v-for="item in filtered" :value="item">
              {{ item.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="form-row" v-show="type.name">
        <div class="col-md-12 md-form">
          <div class="text-center m-t-30">
            <h4 class="m-t-9">{{ type.name }} aún no esta aperturado</h4>
            <p class="text-muted m-t-0 font-12">puede aperturar hoy mismo.</p>
            <m-button
              class="btn-inverse-success"
              @pum="isOpenMode = !isOpenMode"
            >
              Aperturar Ahora
            </m-button>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="form-row">
        <h5 v-show="isNew" class="col-md-12">Aperturando {{ type.name }}</h5>
        <m-input
          type="date"
          id="Desde"
          class="col-md-6"
          v-model="$store.state.cycle.cycle.from"
          v-validate="fromVali"
          :error="errors.first('Desde')"
        />
        <m-input
          type="date"
          id="Hasta"
          class="col-md-6"
          v-model="$store.state.cycle.cycle.to"
          v-validate="toVali"
          :error="errors.first('Hasta')"
        />
      </div>
      <div class="form-row">
        <m-input
          class="col-md-4"
          id="horaIngreso"
          type="time"
          v-validate="'required'"
          v-model="$store.state.cycle.cycle.entry_time"
          label="Hora de Ingreso"
          :error="errors.first('horaIngreso')"
        />
        <m-input
          class="col-md-4"
          id="Tolerancia"
          type="number"
          v-validate="'required|numeric|max_value:20'"
          v-model="$store.state.cycle.cycle.tolerance"
          :error="errors.first('Tolerancia')"
        />
        <m-input
          class="col-md-4"
          id="Mensualidad"
          type="number"
          v-validate="'required|decimal'"
          v-model="$store.state.cycle.cycle.monthly"
          :error="errors.first('Mensualidad')"
        />
      </div>
      <p class="text-accent">{{ fin_message }}</p>
      <a
        class="text-accent"
        type="button"
        v-show="isNew"
        @click="isOpenMode = false"
      >
        Cancelar y Volver atras
      </a>
    </template>
  </modal>
</template>
<script>
import { mapActions } from "vuex";
export default {
  props: {
    isNew: {
      type: Boolean,
      default: true
    },
    ctypes: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      isOpenMode: !this.isNew,
      type: {}
    };
  },
  computed: {
    fromVali() {
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: this.$store.state.cycle.cycle.to,
        after: `${this.$store.getters["fullyear"]}-01-01`
      };
    },
    toVali() {
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: `${parseInt(this.$store.getters["fullyear"]) + 1}-01-01`,
        after: this.$store.state.cycle.cycle.from
      };
    },
    filtered() {
      const types = Object.assign({}, this.ctypes);
      const existing = this.$store.state.cycle.cycles;
      for (let x = 0; x < existing.length; x++) {
        delete types[existing[x].type];
      }
      return Object.keys(types).map((key) => {
        return { code: key, name: types[key] };
      });
    },
    fin_message() {
      if (!this.isNew) {
        if (new Date() > new Date(this.$store.state.cycle.cycle.to)) {
          return "Al finalizar actualizará a sus estudiantes como [finalizado]";
        }
      }
      return "";
    }
  },
  watch: {
    isNew(val) {
      this.isOpenMode = !val;
    }
  },
  methods: {
    ...mapActions("cycle", ["set", "update"]),
    store(data) {
      if (this.isNew) {
        data.type = this.type.code;
        return this.set(data);
      } else {
        return this.update(data);
      }
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.store(this.$store.state.cycle.cycle).then((r) => {
            this.$store.commit("cycle/FETCH_CYCLES", {
              cycles: []
            });
            this.$emit("after");
            this.$snack.success(r);
            $("#newCycle").modal("hide");
          });
        }
      });
    }
  }
};
</script>
