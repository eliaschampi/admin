<template>
  <div class="selectgroup selectgroup-pills">
    <label class="selectgroup-item">
      <input
        type="radio"
        name="persontyperadio"
        value="student"
        class="selectgroup-input"
        checked
        @change="$emit('input', 'student')"
      />
      <span class="selectgroup-button">Estudiante</span>
    </label>
    <label class="selectgroup-item">
      <input
        type="radio"
        name="persontyperadio"
        value="teacher"
        class="selectgroup-input"
        @change="$emit('input', 'teacher')"
      />
      <span class="selectgroup-button">Docente</span>
    </label>
    <label class="selectgroup-item">
      <input
        type="radio"
        name="persontyperadio"
        value="family"
        class="selectgroup-input"
        @change="$emit('input', 'family')"
      />
      <span class="selectgroup-button">Apoderado</span>
    </label>
  </div>
</template>
<script>
export default {
  name: "person-type",
  props: {
    value: String
  }
};
</script>
