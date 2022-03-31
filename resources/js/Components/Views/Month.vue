<template>
  <div class="form-group" style="max-width: 15rem">
    <label for="cashselect">Seleccione un Mes</label>
    <select
      class="form-control form-control-sm"
      id="cashselect"
      v-model="$store.state.month"
      @change="$emit('monthchange')"
    >
      <option :key="item.code" v-for="item in months" :value="item.code">
        {{ item.month }}
      </option>
    </select>
  </div>
</template>
<script>
import months from "../../Data/months.json";
export default {
  name: "Month",
  data() {
    return {
      months
    };
  }
};
</script>
