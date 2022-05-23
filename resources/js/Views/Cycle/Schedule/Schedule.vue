<template>
  <section>
    <card title="Horario" :f="false">
      <m-router
        icon="icon ion-md-add icon-md"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        v-can="'S'"
        :to="{ name: 'new_schedule', params: { degree_code: code } }"
        class="px-2"
        slot="rb"
      />
      <div class="row">
        <div class="col-md-4" v-if="teachers.length">
          <panel :title="teachers_title">
            <template v-for="(item, index) in teachers">
              <div class="d-flex align-items-center rounded" :key="item.dni">
                <div class="avatar">
                  <img
                    v-if="item.person.profile"
                    width="30"
                    class="rounded"
                    :src="`/default/${item.person.profile.image}`"
                  />
                  <img v-else src="/img/logo.png" />
                </div>
                <div class="info ml-1">
                  <router-link
                    :to="{
                      name: 't_schedule',
                      params: { dni: item.dni }
                    }"
                  >
                    {{ item.person.name }}
                  </router-link>
                  <div class="d-flex align-items-center">
                    <span class="text-small d-block">Color</span>
                    <div
                      class="boli ml-1"
                      :style="{
                        backgroundColor: `#${colors[item.dni]}`
                      }"
                    ></div>
                  </div>
                </div>
              </div>
              <hr :key="index" />
            </template>
          </panel>
        </div>
        <div
          class="mt-4 mt-md-0"
          :class="teachers.length ? 'col-md-8' : 'col-md-12'"
        >
          <div class="d-flex">
            <my-section @done="fetchData" />
            <m-button class="ml-auto align-self-center btn-sm" @pum="fetchData">
              Actualizar
            </m-button>
          </div>
          <ul class="nav nav-pills">
            <li class="nav-item pointer" @click="day = 1">
              <div class="nav-link" :class="{ active: day === 1 }">Lunes</div>
            </li>
            <li class="nav-item pointer" @click="day = 2">
              <div class="nav-link" :class="{ active: day === 2 }">Martes</div>
            </li>
            <li class="nav-item pointer" @click="day = 3">
              <div class="nav-link" :class="{ active: day === 3 }">
                Miercoles
              </div>
            </li>
            <li class="nav-item pointer" @click="day = 4">
              <div class="nav-link" :class="{ active: day === 4 }">Jueves</div>
            </li>
            <li class="nav-item pointer" @click="day = 5">
              <div class="nav-link" :class="{ active: day === 5 }">Viernes</div>
            </li>
          </ul>
          <hr />
          <m-table
            :columns="['#', 'Curso', 'Desde', 'Hasta', 'Acciones']"
            :data="filtered"
            :head="false"
          >
            <template slot="data">
              <tr :key="item.code" v-for="item in filtered">
                <td>
                  <div
                    class="boli"
                    :style="{
                      backgroundColor: `#${colors[item.op.teacher_dni]}`
                    }"
                  ></div>
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
        </div>
      </div>
    </card>
    <add-item :schedule="schedule" :days="days" @save="save" :is-new="false" />
  </section>
</template>
<script>
import mySection from "@/Components/Views/mySection";
import t_api from "@/Api/teacher";
import sh_api from "@/Api/schedule";
import { mapGetters } from "vuex";
import days from "@/Data/weekDays.json";
import { deleteSH } from "@/Mixins/utils";
import AddItem from "../../Cycle/Schedule/AddItem";

export default {
  name: "schedule",
  mixins: [deleteSH],
  components: { mySection, AddItem },
  data() {
    return {
      teachers: [],
      load: false,
      schedules: [],
      schedule: null,
      colors: {},
      days,
      day: 1
    };
  },
  mounted() {
    this.fetchTech();
  },
  watch: {
    cycle_code() {
      this.fetchTech();
    }
  },
  computed: {
    ...mapGetters("degree", ["code"]),
    cycle_code() {
      return this.$store.state.degree.degree.cycle_code;
    },
    teachers_title() {
      return `Docentes de ${this.$options.filters.level(this.cycle_code)}`;
    },
    filtered() {
      return this.schedules.filter((item) => item.day === this.day);
    }
  },
  methods: {
    fetchTech() {
      t_api.fetchByCycle(this.cycle_code).then((r) => {
        this.teachers = r.data.values;
        r.data.values.forEach((item) => {
          this.colors[item.dni] = Math.floor(Math.random() * 16777215).toString(
            16
          );
        });
      });
    },
    edit(item) {
      this.schedule = { ...item };
      $("#newSchedule").modal("show");
    },
    fetchData() {
      this.load = true;
      sh_api
        .fetchMain(this.$store.getters["section/code"])
        .then((r) => {
          this.schedules = r.data.values;
        })
        .finally(() => {
          this.load = false;
        });
    },
    async save(schedule) {
      return await sh_api.update(schedule).then((r) => {
        this.fetchData();
        this.$snack.success(r.data);
        $("#newSchedule").modal("hide");
        this.schedule = null;
      });
    }
  }
};
</script>
<style>
.hover > li:hover {
  cursor: pointer;
  background-color: aliceblue;
}
</style>
