<template>
  <card :title="cycleTypes[cycle.type]">
    <div slot="subtitle">{{ cDaysPassed }} dias transcurridos</div>
    <div class="progress mb-2" style="height: 25px">
      <div
        :aria-valuenow="value"
        :style="{ width: value + '%', height: '25px' }"
        aria-valuemax="100"
        aria-valuemin="0"
        class="
          progress-bar progress-bar-striped progress-bar-animate
          bg-success
        "
        role="progressbar"
      >
        <b>{{ value + "%" }}</b>
      </div>
    </div>
    <div class="card-text">
      <b>Desde:</b>
      {{ cycle.from | date }}
    </div>
    <div class="card-text">
      <b>Hasta:</b>
      {{ cycle.to | date }}
    </div>
    <div class="card-text">
      <b>Mensualidad:</b>
      {{ cycle.monthly | currency }}
    </div>
    <div class="card-text">
      <b>Horario de Ingreso:</b>
      {{ cycle.entry_time | time }} con {{ cycle.tolerance }} minutos de
      tolerancia
    </div>
    <template slot="foot">
      <m-button
        @pum="$emit('edit')"
        size="btn-sm"
        v-can="'A'"
        color="btn-inverse-accent"
        icon="icon ion-md-create"
      >
        Modificar
      </m-button>
      <m-router
        :to="{ name: 'sumary', params: { level: cycle.type } }"
        color="btn-inverse-primary"
        size="btn-sm"
      >
        Reporte anual
      </m-router>
    </template>
  </card>
</template>
<script>
import { daysPassed, porsentaje } from "@/Helpers/date";
export default {
  name: "CycleItem",
  props: {
    cycle: {
      type: Object,
      default: () => {}
    },
    cycleTypes: Object
  },
  computed: {
    cDaysPassed() {
      return daysPassed(this.cycle.from, this.cycle.to);
    },
    value() {
      return Number(porsentaje(this.cycle.from, this.cycle.to)).toFixed(2);
    }
  }
};
</script>
