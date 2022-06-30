<template>
  <div class="mt-3">
    <ul class="nav nav-pills">
      <li
        class="nav-item pointer"
        v-for="d in fdays"
        :key="d.code"
        @click="day = d.code"
      >
        <div class="nav-link" :class="{ active: day === d.code }">
          {{ d.day }}
        </div>
      </li>
    </ul>
    <hr />
    <m-table :columns="columns" :data="filtered" :head="false">
      <template slot="data">
        <tr :key="item.code" v-for="item in filtered">
          <td>{{ item.stext }}</td>
          <td>{{ item.course }}</td>
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
import { OPSH } from "../../Mixins/utils";
import AddItem from "../Cycle/Schedule/AddItem.vue";
export default {
  name: "ScheduleTeacher",
  components: { AddItem },
  mixins: [fetchData, OPSH],
  data() {
    return {
      columns: ["Grado y Sec.", "Curso", "Desde", "Hasta", "Acciones"],
      days
    };
  },
  computed: {
    ...mapState("teacher", ["teacher"]),
    filtered() {
      return this.schedules.filter(
        (item) => item.day === this.day || item.day === 0
      );
    }
  },
  methods: {
    fetchData() {
      const decorators = {
        all: "Toda la institución",
        PRI: "Primaria",
        SEC: "Secundaria",
        GE5: "Grupo Carrión",
        OP1: "Primera opción",
        OP2: "Primera opción",
        OR1: "Ordinario",
        OR2: "Ordinario",
        IN1: "Intensivo",
        IN2: "Intensivo"
      };
      api.fetchByTeacher(this.teacher.dni).then(({ data }) => {
        this.schedules = data.values.map((item) => {
          const stv = item.op.sts.split(/[{}]|,/g).slice(1, -1);
          const st = stv[0];
          let stext = "";
          if (st.length === 10) {
            stext = `${st.substr(-2)} de ${decorators[st.substr(4, 3)]}`;
          } else {
            stext = decorators[st];
          }

          if (stv.length > 1) {
            stext += " y otros";
          }

          return {
            code: item.code,
            day: item.day,
            from_time: item.from_time,
            to_time: item.to_time,
            course: item.op.course.name,
            stext
          };
        });
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
