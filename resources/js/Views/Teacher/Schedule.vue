<template>
  <div class="mt-3">
    <m-table :columns="columns" :data="schedules" :head="false">
      <template slot="data">
        <tr :key="item.code" v-for="item in schedules">
          <td>
            {{ item.op.section_code | full }}
          </td>
          <td>{{ item.op.course.name }}</td>
          <td>{{ days[item.day].day }}</td>
          <td>{{ item.from_time }}</td>
          <td>{{ item.to_time }}</td>
          <td>
            <i
              class="icon ion-ios-checkmark-circle icon-sm text-success"
              v-if="item.state"
            >
            </i>
          </td>
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
    <div class="d-flex justify-content-between">
      <div class="button-group">
        <m-router
          color="btn-light"
          size="btn-sm"
          slot="rb"
          :to="{
            name: 'schedule',
            params: { degree_code: $store.getters['degree/code'] }
          }"
        >
          Ir a horarios
        </m-router>
      </div>
    </div>
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
      columns: [
        "Sección",
        "Curso",
        "Dia",
        "Desde",
        "Hasta",
        "Asis.",
        "Acciones"
      ],
      schedules: [],
      schedule: null,
      days
    };
  },
  computed: mapState("teacher", ["teacher"]),
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
