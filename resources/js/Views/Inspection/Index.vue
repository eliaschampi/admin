<template>
  <card
    :title="`Registro de ${decorated_types[i_type]} del año ${$store.getters['fullyear']}`"
  >
    <m-button
      slot="rb"
      color="btn-inverse-accent"
      class="btn-icon"
      icon="icon ion-md-add"
    >
    </m-button>
    <div class="selectgroup selectgroup-pills">
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="p"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Permisos</span>
      </label>
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="r"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Requisas</span>
      </label>
      <label class="selectgroup-item">
        <input
          type="radio"
          name="mode"
          value="l"
          class="selectgroup-input"
          v-model="i_type"
        />
        <span class="selectgroup-button">Llamadas</span>
      </label>
    </div>
    <div class="row">
      <m-table
        class="col-md-12"
        :data="filtered"
        :columns="columns_filtered"
        v-model="buscado"
      >
        <template v-slot:data="{ rows }">
          <tr v-for="item in rows" :key="item.code">
            <td>{{ item.code }}</td>
            <td>{{ decorated_types[item.inspection_type] }}</td>
            <td>
              <template v-if="item.user">
                <b>{{ `${item.user.name}` }}</b>
              </template>
              <template v-else>
                <i class="text-muted"> Enviado desde web app </i>
              </template>
            </td>
            <td>
              {{ dest_types[item.entity_type].label }}
            </td>
            <td>
              <router-link
                class="font-weight-bold"
                :to="{
                  name: dest_types[item.entity_type].route,
                  params: { dni: item.person.dni }
                }"
              >
                {{ `${item.person.name} ${item.person.lastname}` }}
              </router-link>
            </td>
            <td>
              {{ item.created_at | datetim }}
            </td>
            <td>
              <div
                :class="[
                  'badge',
                  `badge-${decorated_states[item.state].color}`
                ]"
              >
                {{ decorated_states[item.state].label }}
              </div>
            </td>
            <td>
              <span v-show="item.additional">
                {{ new Date(item.additional).toLocaleDateString() }}
              </span>
            </td>
          </tr>
        </template>
      </m-table>
    </div>
    <p slot="foot" class="text-center text-primary">
      {{ `Hay ${inspections.length} registros` }}
    </p>
  </card>
</template>
<script>
import api from "../../Api/inspection";
export default {
  name: "Inspection",
  data() {
    return {
      inspections: [],
      i_type: "p",
      buscado: "",
      columns: [
        "Nro",
        "Tipo",
        "Usuario",
        "Dest.",
        "Nombre",
        "Fecha de registro",
        "Estado"
      ],
      decorated_types: {
        p: "permisos",
        l: "llamadas",
        r: "requisas"
      },
      dest_types: {
        s: {
          label: "Estudiante",
          route: "student_profile"
        },
        t: {
          label: "Docente",
          route: "teacher_profile"
        },
        f: {
          label: "Apoderado",
          route: "family_profile"
        }
      },
      loading: false,
      decorated_states: []
    };
  },
  watch: {
    i_type(val) {
      this.fetchData();
    }
  },
  mounted() {
    this.fetchTypes().then(() => {
      this.fetchData();
    });
  },
  computed: {
    filtered() {
      return this.inspections.filter((item) =>
        new RegExp(this.buscado, "i").test(item.description)
      );
    },
    columns_filtered() {
      const last_col_type = {
        p: "Fecha de permiso",
        r: "Obj. requizado",
        l: "Nro cel. activo"
      };
      return [...this.columns, last_col_type[this.i_type]];
    }
  },
  methods: {
    async fetchData() {
      this.inspections = [];
      this.loading = true;
      const { data } = await api.fetchByType(this.i_type);
      this.inspections = data.values;
      this.loading = false;
    },
    async fetchTypes() {
      const { data } = await api.fetchStates();
      this.decorated_states = data.values;
    }
  }
};
</script>