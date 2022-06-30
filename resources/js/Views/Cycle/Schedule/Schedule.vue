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
        <m-button class="align-self-center ml-1 btn-sm" @pum="fetchData">
          Actualizar
        </m-button>
      </div>
      <div class="alert alert-info">
        Los colores asignados a los docentes representan el curso que esta
        dictando en esta sección o grupo.
        <br />
        ⚡Precione en la foto del docente para ver su horario
        <br />
        ⚡Presione en el icono <span class="icon ion-md-flower"></span>
        para modificar [Docente - Curso]
      </div>
      <div class="d-flex">
        <div
          v-for="item in teachers"
          :key="item.dni"
          class="d-flex align-items-center bg-light rounded-md p-2 ml-2 mr-2 mb-2"
        >
          <router-link
            class="avatar pointer"
            tag="div"
            :to="{
              name: 't_schedule',
              params: { dni: item.dni }
            }"
          >
            <img
              v-if="item.person.profile"
              width="33"
              :style="{
                borderRadius: '50%',
                border: `3px solid #${colors[item.dni]}`
              }"
              :src="`/default/${item.person.profile.image}`"
            />
            <img v-else src="/img/logo.png" />
          </router-link>
          <div class="info ml-1">
            <div class="font-weight-medium">
              {{ item.person.name }}
              <span
                data-tooltip="Modificar"
                @click="handleChangeTeacherShow(item)"
              >
                <i class="icon ion-md-flower pointer"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
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
    </card>
    <add-item :schedule="schedule" :days="days" @save="save" :is-new="false" />
    <modal
      id="myidUOP"
      title="Modificar horario del docente"
      :disabled="disabledUpdateModal"
      @sub="handleUpdateOp"
    >
      <template v-if="selected.person">
        <b>Docente:</b>
        {{ `${selected.person.name} ${selected.person.lastname}` }}
      </template>
      <hr />
      <alert type="alert-danger">
        Eliminar el horario del docente establecido para todos los cursos.
        (tambien se borrará el contenido de su plataforma)
        <strong class="alert-link pointer" @click="handleDeleteOP">
          Eliminar ahora
        </strong>
      </alert>
      <m-input-btn
        id="changetid"
        label="O cambiar de docente"
        placeholder="Ingresa el DNI del nuevo docente"
        icon="icon ion-md-search"
        @onbtn="handleSearchTeacher"
      />
      <div class="mt-2 text-success font-weight-bold" v-show="person.dni">
        Nuevo docente: {{ person.full_name }}
      </div>
      <alert class="mt-2">
        Selecciona los cursos a las cuales deseas modificar el docente
      </alert>
      <ul style="list-style: none" class="bg-light rounded-md pt-2">
        <li v-for="item in courses" :key="item.code">
          <div class="form-check">
            <input
              type="checkbox"
              aria-label="oparial"
              :value="item.code"
              v-model="ops"
              :id="`opc${item.code}`"
            />
            <label :for="`opc${item.code}`" class="user-select-none">
              {{ item.course.name }}
            </label>
          </div>
        </li>
      </ul>
    </modal>
  </section>
</template>
<script>
import mySection from "@/Components/Views/mySection";
import t_api from "@/Api/teacher";
import op_api from "@/Api/op";
import sh_api from "@/Api/schedule";
import { mapGetters } from "vuex";
import days from "@/Data/weekDays.json";
import { OPSH } from "@/Mixins/utils";
import AddItem from "../../Cycle/Schedule/AddItem";
export default {
  name: "schedule",
  mixins: [OPSH],
  components: { mySection, AddItem },
  data() {
    return {
      teachers: [],
      person: {
        full_name: "",
        dni: ""
      },
      ops: [],
      courses: [],
      load: false,

      colors: {},
      selected: {},
      days
    };
  },
  computed: {
    ...mapGetters("section", ["code"]),
    filtered() {
      return this.schedules.filter(
        (item) => item.day === this.day || item.day === 0
      );
    },
    disabledUpdateModal() {
      return this.ops.length === 0 || !this.person.dni;
    }
  },
  methods: {
    async handleSearchTeacher() {
      const el = document.getElementById("changetid");
      if (el && el.value.length === 8 && !isNaN(el.value)) {
        try {
          const { data } = await t_api.show(el.value);
          const {
            dni,
            person: { name, lastname }
          } = data.value;
          this.person.full_name = `${name} ${lastname}`;
          this.person.dni = dni;
        } catch {
          this.$snack.warning("Docente no encontrado");
        }
      } else {
        this.$snack.warning("Ingresa DNI correcto");
      }
    },
    async handleChangeTeacherShow(item) {
      this.selected = item;
      $("#myidUOP").modal("show");
      const { data } = await op_api.fetchByTeacher(item.dni);
      this.courses = data.values;
    },
    async handleDeleteOP() {
      if (!this.selected.dni) return;
      const { data } = await op_api.destroy(this.selected.dni);
      this.$snack.success(data.message);
      this.hideUpdateModal();
    },
    hideUpdateModal() {
      setTimeout(() => {
        $("#myidUOP").modal("hide");
        this.selected = {};
        this.fetchData();
        this.fetchSchedules();
      }, 1000);
    },
    edit(item) {
      this.schedule = { ...item };
      $("#newSchedule").modal("show");
    },
    async fetchSchedules() {
      const { data } = await sh_api.fetchMain(this.code);
      this.schedules = data.values;
      this.load = false;
    },
    async fetchData() {
      this.load = true;
      const { data } = await t_api.fetchBySection(this.code);
      this.teachers = data.values;
      data.values.forEach(({ dni }) => {
        //eslint-disable-next-line
        this.colors[dni] = (0x1000000 + Math.random() * 0xffffff)
          .toString(16)
          .substr(1, 6);
      });
      this.fetchSchedules();
    },
    async save(schedule) {
      return await sh_api.update(schedule).then((r) => {
        this.fetchSchedules();
        this.$snack.success(r.data);
        $("#newSchedule").modal("hide");
        this.schedule = null;
      });
    },
    async handleUpdateOp() {
      const { data } = await op_api.update(this.person.dni, this.ops);
      this.$snack.success(data.message);
      this.hideUpdateModal();
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
