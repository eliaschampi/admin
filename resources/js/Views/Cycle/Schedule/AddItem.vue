<template>
  <modal
    @sub="add"
    @closed="onClosed"
    :btnName="isNew ? 'Agregar' : 'Modificar'"
    id="newSchedule"
    title="Agregar horario"
  >
    <div class="row">
      <div class="form-group col-sm-12">
        <label for="dayselect">Seleccione un día</label>
        <select class="form-control" id="dayselect" v-model="sched.day">
          <option :key="item.code" v-for="item in days" :value="item.code">
            {{ item.day }}
          </option>
        </select>
      </div>
    </div>
    <div class="row">
      <m-input
        class="col-md-6"
        type="time"
        id="Desde"
        v-validate="'required'"
        v-model="sched.from_time"
        :error="errors.first('Desde')"
      />
      <m-input
        class="col-md-6"
        type="time"
        id="Hasta"
        v-validate="'required'"
        v-model="sched.to_time"
        :error="errors.first('Hasta')"
      />
    </div>
  </modal>
</template>
<script>
export default {
  name: "AddItem",
  props: {
    schedule: Object,
    days: {
      type: Object,
      default: () => {}
    },
    isNew: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      sched: {}
    };
  },
  watch: {
    schedule(val) {
      if (val) {
        this.sched = this.schedule;
      }
    }
  },
  methods: {
    onClosed() {
      this.sched = {
        day: 1,
        from_time: this.sched.from_time || "08:00",
        to_time: this.sched.to_time || "14:00",
        state: true
      };
    },
    add() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.$emit("save", this.sched, this.isNew);
        }
      });
    }
  }
};
</script>
