<template>
  <section>
    <card title="Horario" :f="false">
      <m-router
        icon="icon ion-md-add icon-md"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        v-can="'S'"
        :to="{ name: 'new_schedule' }"
        class="px-2"
        slot="rb"
      />

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
      <div class="row">
        <div class="col-md-3">
          <div
            v-for="item in teachers"
            :key="item.dni"
            class="d-flex align-items-center bg-light rounded-md p-2 mb-1"
          >
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
        </div>
        <div class="col-md-9">
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
  computed: {
    ...mapGetters("section", ["code"]),
    filtered() {
      return this.schedules.filter((item) => item.day === this.day);
    }
  },
  methods: {
    edit(item) {
      this.schedule = { ...item };
      $("#newSchedule").modal("show");
    },
    async fetchData() {
      this.load = true;
      const { code } = this;
      const { data: onedata } = await t_api.fetchBySection(code);
      this.teachers = onedata.values;
      onedata.values.forEach(({ dni }) => {
        this.colors[dni] = Math.floor(Math.random() * 16777215).toString(16);
      });
      const { data } = await sh_api.fetchMain(code);
      this.schedules = data.values;
      this.load = false;
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
