<template>
  <div class="mt-3">
    <div class="selectgroup selectgroup-pills">
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="extra"
          class="selectgroup-input"
          v-model="type"
        />
        <span class="selectgroup-button">Información adicional</span>
      </label>
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="ailment"
          class="selectgroup-input"
          v-model="type"
        />
        <span class="selectgroup-button">Registros médicos</span>
      </label>
    </div>
    <component :is="type" />
  </div>
</template>
<script>
import Extra from "./components/Extra.vue";
import Ailment from "./components/Ailment.vue";
export default {
  components: { Extra, Ailment },
  data() {
    return {
      type: "extra"
    };
  }
};
</script>
