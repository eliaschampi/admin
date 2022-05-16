<template>
  <div class="selectgroup selectgroup-pills">
    <label class="selectgroup-item">
      <input
        type="radio"
        name="mode"
        value="p"
        class="selectgroup-input"
        checked
        @change="$emit('input', 'p')"
      />
      <span class="selectgroup-button">Permisos</span>
    </label>
    <label class="selectgroup-item">
      <input
        type="radio"
        name="mode"
        value="r"
        class="selectgroup-input"
        @change="$emit('input', 'r')"
      />
      <span class="selectgroup-button">Requisas</span>
    </label>
    <label class="selectgroup-item">
      <input
        type="radio"
        name="mode"
        value="l"
        class="selectgroup-input"
        @change="$emit('input', 'l')"
      />
      <span class="selectgroup-button">Llamadas</span>
    </label>
  </div>
</template>
<script>
export default {
  name: "inspection-type",
  props: {
    value: String
  }
};
</script>
