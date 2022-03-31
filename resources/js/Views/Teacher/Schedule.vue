<template>
  <div class="mt-3">
    <ul class="nav nav-pills">
      <li class="nav-item pointer" @click="day = 1">
        <div class="nav-link" :class="{ active: day === 1 }">Lunes</div>
      </li>
      <li class="nav-item pointer" @click="day = 2">
        <div class="nav-link" :class="{ active: day === 2 }">Martes</div>
      </li>
      <li class="nav-item pointer" @click="day = 3">
        <div class="nav-link" :class="{ active: day === 3 }">Miercoles</div>
      </li>
      <li class="nav-item pointer" @click="day = 4">
        <div class="nav-link" :class="{ active: day === 4 }">Jueves</div>
      </li>
      <li class="nav-item pointer" @click="day = 5">
        <div class="nav-link" :class="{ active: day === 5 }">Viernes</div>
      </li>
    </ul>
    <hr />
    <m-table :columns="columns" :data="filtered" :head="false">
      <template slot="data">
        <tr :key="item.code" v-for="item in filtered">
          <td>
            {{ item.op.section_code | full }}
          </td>
          <td>{{ item.op.course.name }}</td>
          <td>{{ item.from_time }}</td>
          <td>{{ item.to_time }}</td>
          <td v-can="'S'">
            <m-action @action="edit(item)" />
            <m-action
              icon="trash"
              color="danger"
              tool="Eliminar"
              @action="deleteSh(item)"
            />
          </td>
        </tr>
      </template>
    </m-table>
    <m-router
      color="btn-outline-secondary"
      size="btn-sm"
      :to="{
        name: 'schedule',
        params: { degree_code: $store.getters['degree/code'] }
      }"
    >
      Horarios por sección
    </m-router>
    <add-item :schedule="schedule" :days="days" @save="save" :is-new="false" />
  </div>
</template>
<script>
import { mapState } from "vuex";
import api from "../../Api/schedule";
import days from "../../Data/weekDays.json";
import { fetchData } from "../../Mixins";
import { deleteSH } from "../../Mixins/utils";
import AddItem from "../Cycle/Schedule/AddItem.vue";
export default {
  name: "ScheduleTeacher",
  components: { AddItem },
  mixins: [fetchData, deleteSH],
  data() {
    return {
      columns: ["Grado y Sec.", "Curso", "Desde", "Hasta", "Acciones"],
      day: 1,
      schedules: [],
      schedule: null,
      days
    };
  },
  computed: {
    ...mapState("teacher", ["teacher"]),
    filtered() {
      return this.schedules.filter((item) => item.day === this.day);
    }
  },
  methods: {
    fetchData() {
      api.fetchByTeacher(this.teacher.dni).then((r) => {
        this.schedules = r.data.values;
      });
    },
    edit(item) {
      this.schedule = { ...item };
      $("#newSchedule").modal("show");
    },
    async save(schedule) {
      return await api.update(schedule).then((r) => {
        this.fetchData();
        this.$snack.success(r.data);
        $("#newSchedule").modal("hide");
        this.schedule = null;
      });
    }
  }
};
</script>
