<template>
  <section>
    <panel>
      <div class="selectgroup selectgroup-pills">
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{
            name: 'attendance_student',
            params: { dni: $route.params.dni }
          }"
        >
          <input
            :checked="$route.name === 'attendance_student'"
            type="radio"
            name="mode"
            value="a"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Asistencia</span>
        </router-link>
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{
            name: 'inspection_student',
            params: { type: 'p', dni: $route.params.dni }
          }"
        >
          <input
            :checked="$route.params.type === 'p'"
            type="radio"
            name="mode"
            value="p"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Permisos</span>
        </router-link>
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{ name: 'justify_student', params: { dni: $route.params.dni } }"
        >
          <input
            :checked="$route.name === 'justify_student'"
            type="radio"
            name="mode"
            value="j"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Justificaciones</span>
        </router-link>
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{
            name: 'incidence_student',
            params: { dni: $route.params.dni }
          }"
        >
          <input
            :checked="$route.name === 'incidence_student'"
            type="radio"
            name="mode"
            value="i"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Incidencias</span>
        </router-link>
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{
            name: 'attention_student',
            params: { dni: $route.params.dni }
          }"
        >
          <input
            :checked="$route.name === 'attention_student'"
            type="radio"
            name="mode"
            value="t"
            class="selectgroup-input"
          />
          <span class="selectgroup-button">Atenciones</span>
        </router-link>
        <router-link
          tag="label"
          class="selectgroup-item"
          :to="{
            name: 'inspection_student',
            params: { type: 'r', dni: $route.params.dni }
          }"
        >
          <input
            :checked="$route.params.type === 'r'"
            type="radio"
            name="mode"
            value="r"
            class="selectgroup-input"
          />
          <span class="selectgroup-button"> Requisas </span>
        </router-link>
      </div>
      <hr />
      <router-view />
    </panel>
  </section>
</template>
<script>
export default {
  name: "history"
};
</script>
