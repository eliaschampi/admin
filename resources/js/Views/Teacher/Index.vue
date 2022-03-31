<template>
  <card title="Registro general de Docentes">
    <template slot="rb">
      <m-button
        @pum="cards"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-md-cloud-download"
      />
      <m-router
        :to="{ name: 'new_t' }"
        color="btn-inverse-accent btn-icon"
        icon="icon ion-md-add"
        v-can="'S'"
      >
      </m-router>
    </template>
    <div class="row">
      <m-table
        :columns="columns"
        :data="teachers"
        :fetch="fetchAll"
        class="col-md-12"
        v-model="buscado"
      >
        <template slot="data">
          <tr :key="item.dni" v-for="item in filtered">
            <td>{{ item.dni }}</td>
            <td>
              <span
                @click="goo(item)"
                class="font-weight-medium text-primary pointer"
              >
                {{ `${item.person.name} ${item.person.lastname}` }}
              </span>
            </td>
            <td>
              <div class="switch">
                <input
                  type="checkbox"
                  disabled
                  :id="'tg' + item.dni"
                  :checked="item.state"
                />
                <label :for="'tg' + item.dni" class="success">Toggle</label>
              </div>
            </td>
            <td>{{ specialties[item.specialty] }}</td>
            <td>
              <router-link
                :to="{
                  name: 'new_t',
                  params: { dni: item.dni }
                }"
                class="text-primary"
                v-can="'S'"
              >
                <i class="icon ion-md-create icon-md fa-2x"></i>
              </router-link>
            </td>
          </tr>
        </template>
        <div slot="foot" class="d-flex justify-content-center">
          <pagination :data="pagination" @pagination-change-page="fetchAll" />
        </div>
      </m-table>
    </div>
    <p slot="foot" class="text-center font-weight-medium text-primary">
      Total: {{ pagination.total }} docentes
    </p>
  </card>
</template>

<script>
import api from "../../Api/teacher";
import mainApi from "../..//Api/main";
export default {
  data() {
    return {
      teacher_dni: "",
      buscado: "",
      specialties: [],
      teachers: [],
      pagination: [],
      columns: [
        "DNI",
        "Apellidos y Nombre",
        "Acceso",
        "Especialidad",
        "Acciones"
      ]
    };
  },
  created() {
    mainApi.fetchByTagsPlucked("cy.m_doc_cs.d").then((r) => {
      this.specialties = r.data.configs;
    });
  },
  computed: {
    filtered() {
      const matcher = new RegExp(this.buscado, "i");
      return this.teachers.filter((item) =>
        matcher.test([item.person.name, item.person.lastname].join())
      );
    }
  },
  methods: {
    goo(item) {
      this.$store.commit("teacher/FETCH_TEACHER", { teacher: item });
      this.$router.push({
        name: "teacher_profile",
        params: { dni: item.dni }
      });
    },
    async fetchAll(page = 1) {
      const { data } = await api.fetchAll(page);
      const all = data.values;
      this.teachers = [...all.data];
      delete all.data;
      this.pagination = all;
    },
    cards() {
      mainApi
        .printCard({
          mode: "teacher",
          identifier: "teacher"
        })
        .then((r) => {
          this.$downl(r.data, "Carnet de docentes", ".pdf");
        });
    }
  }
};
</script>
