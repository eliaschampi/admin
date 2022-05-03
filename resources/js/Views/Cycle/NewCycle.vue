<template>
  <modal
    :title="
      isOpenMode && isNew ? 'Aperturando ' + type.name : 'Niveles Académicos'
    "
    @sub="save"
    @closed="onClosed"
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
        <m-input
          type="date"
          id="Desde"
          class="col-md-6"
          v-model="cycle.from"
          v-validate="fromVali"
          :error="errors.first('Desde')"
        />
        <m-input
          type="date"
          id="Hasta"
          class="col-md-6"
          v-model="cycle.to"
          v-validate="toVali"
          :error="errors.first('Hasta')"
        />
      </div>
      <div class="form-row">
        <m-input
          class="col-md-6"
          id="Mensualidad"
          type="number"
          v-validate="'required|decimal'"
          v-model="cycle.monthly"
          :error="errors.first('Mensualidad')"
        />
      </div>
      <template v-for="att in cycle.attendance">
        <div class="form-row" :key="att.order">
          <m-input
            class="col-md-4"
            id="horaIngreso"
            issm
            type="time"
            v-model="att.entry_time"
            label="Hora de Ingreso"
          />
          <m-input
            class="col-md-4"
            id="Tolerancia"
            issm
            type="number"
            v-model="att.tolerance"
          />
          <span
            @click="addAttendance"
            v-show="
              att.order === cycle.attendance.length &&
              cycle.attendance.length < 3
            "
            class="icon ion-md-add icon-md align-self-center ml-1 pointer text-success"
          ></span>
          <span
            @click="removeAttendance"
            v-show="
              att.order === cycle.attendance.length &&
              cycle.attendance.length > 1
            "
            class="icon ion-md-remove icon-md align-self-center ml-1 pointer text-danger"
          ></span>
        </div>
      </template>
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
      type: {},
      cycle: {}
    };
  },
  watch: {
    isNew(val) {
      this.isOpenMode = !val;
    }
  },
  computed: {
    fromVali() {
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: this.cycle.to,
        after: `${this.$store.getters["fullyear"]}-01-01`
      };
    },
    toVali() {
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: `${parseInt(this.$store.getters["fullyear"]) + 1}-01-01`,
        after: this.cycle.from
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
    }
  },
  methods: {
    ...mapActions("cycle", ["set", "update"]),
    updateMyCycle(value) {
      this.cycle = value;
    },
    addAttendance() {
      this.cycle.attendance.push({
        order: this.cycle.attendance.length + 1,
        entry_time: "07:00",
        tolerance: 5
      });
    },
    removeAttendance() {
      this.cycle.attendance.pop();
    },
    onClosed() {
      if (this.isNew) {
        this.isOpenMode = false;
      }

      this.$emit("close");
    },
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
          this.store(this.cycle).then((r) => {
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
