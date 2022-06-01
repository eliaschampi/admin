<template>
  <div>
    <alert class="mb-4">
      Los permisos y las justificaciónes son enviados desde la plataforma por el
      usuario, aunque también pueden ser generados por los administradores.
      Revisa y gestiona las justificaciones aqui
    </alert>
    <m-table
      :columns="columns"
      :load="loading"
      :head="false"
      :data="justifications"
    >
      <template v-slot:data="{ rows }">
        <tr v-for="item in rows" :key="item.code">
          <td>{{ item.attendance_code }}</td>
          <td>{{ item.created_at | month }}</td>
          <td>{{ item.attendance.created_at | month }}</td>
          <td>
            <span class="text-dotted text-primary">
              {{ item.description }}
            </span>
          </td>
          <td>
            <b>
              {{ item.attendance.state }}
            </b>
          </td>
          <td>
            <m-action
              color="info"
              icon="eye"
              tool="Ver Detalles"
              @action="showModal(item)"
            />
          </td>
        </tr>
      </template>
    </m-table>
    <justification
      :code="selected.attendance_code"
      :state="original_state"
      :justification="selected"
      @closed="jusClosed"
      @updated="jusUpdated"
    />
  </div>
</template>
<script>
import api from "../../Api/justification";
import { fetchData } from "../../Mixins";
import Justification from "./components/Justification.vue";
export default {
  components: { Justification },
  name: "justification-student",
  mixins: [fetchData],
  data() {
    return {
      justifications: [],
      loading: false,
      selected: {},
      original_state: null,
      columns: [
        "Nro",
        "Enviado el:",
        "Para fecha:",
        "Justificación",
        "Estado de asis:",
        "#"
      ]
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      const { data } = await api.fetchByEntity(this.$route.params.dni);
      this.justifications = data.values;
      this.loading = false;
    },
    showModal(item) {
      this.original_state = item.attendance.state;
      this.selected = Object.assign({}, item);
      $("#jus").modal("show");
    },
    jusClosed() {
      this.selected = {};
      $("#jus").modal("hide");
    },
    jusUpdated(data) {
      this.selected = {};
      this.$snack.success(data);
      $("#jus").modal("hide");
      this.fetchData();
    }
  }
};
</script>