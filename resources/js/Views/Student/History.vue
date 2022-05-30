<template>
  <section>
    <panel>
      <div class="selectgroup selectgroup-pills">
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('attendance_student')"
        >
          <input
            :checked="$route.name === 'attendance_student'"
            type="radio"
            name="mode"
            value="a"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Asistencia</span>
        </label>
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('incidence_student')"
        >
          <input
            :checked="$route.name === 'incidence_student'"
            type="radio"
            name="mode"
            value="i"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Incidencias</span>
        </label>
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('attention_student')"
        >
          <input
            :checked="$route.name === 'attention_student'"
            type="radio"
            name="mode"
            value="t"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Atenciones</span>
        </label>
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('inspection_student')"
        >
          <input
            :checked="$route.name === 'inspection_student'"
            type="radio"
            name="mode"
            value="p"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Permisos</span>
        </label>
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('justify_student')"
        >
          <input
            :checked="$route.name === 'justify_student'"
            type="radio"
            name="mode"
            value="j"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Justificaciones</span>
        </label>
        <label
          class="selectgroup-item"
          @click.once="handleGoToRoute('inspection_student')"
        >
          <input
            :checked="$route.name === 'inspection_student'"
            type="radio"
            name="mode"
            value="r"
            class="selectgroup-input"
          />
          <span class="selectgroup-button"> Requisas </span>
        </label>
      </div>
      <hr />
      <router-view />
    </panel>
  </section>
</template>
<script>
export default {
  name: "history",
  methods: {
    handleGoToRoute(val) {
      if (this.$route.name === val) return;
      this.$router.push({ name: val, params: { dni: this.$route.params.dni } });
    }
  }
};
</script>