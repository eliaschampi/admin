<template>
  <section>
    <card
      :title="`Registro de ${decorated_types[i_type]} del año ${$store.getters['fullyear']}`"
    >
      <m-router
        slot="rb"
        color="btn-inverse-accent"
        class="btn-icon"
        icon="icon ion-md-add"
        :to="{ name: 'new_inspection' }"
      >
      </m-router>
      <inspection-type v-model="i_type" />
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
                {{ ptypes[item.entity_type].label }}
              </td>
              <td>
                <i class="icon ion-md-people icon-sm text-accent"></i>
                <router-link
                  class="font-weight-bold text-primary"
                  :to="{
                    name: ptypes[item.entity_type].route,
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
                <template v-if="item.type === 'p'">
                  <span v-show="item.additional">
                    {{ new Date(item.additional).toLocaleDateString() }}
                  </span>
                </template>
                <template v-else>
                  {{ item.additional }}
                </template>
              </td>
              <td>
                <m-action @action="handleEditClick(item)" />
                <m-action
                  @action="handlePrintAction(item)"
                  icon="print"
                  color="success"
                />
                <m-action
                  @action="handleDeleteClick(item)"
                  icon="trash"
                  color="danger"
                />
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <p slot="foot" class="text-center text-primary">
        {{ `Hay ${inspections.length} registros` }}
      </p>
    </card>
    <update-inspection ref="updatemodal" @save="handleUpdate" />
  </section>
</template>
<script>
import api from "../../Api/inspection";
import InspectionType from "./components/InspectionType.vue";
import ptypes from "../../Data/personTypes.json";
import UpdateInspection from "./components/UpdateInspection.vue";
export default {
  components: { InspectionType, UpdateInspection },
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
      ptypes,
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
      return [...this.columns, last_col_type[this.i_type], "Acciones"];
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
    async handlePrintAction(item) {
      const { data } = await api.print(item.code);
      this.$downl(data, `Informe Nro ${item.code}`);
    },
    async fetchTypes() {
      const { data } = await api.fetchStates();
      this.decorated_states = data.values;
    },
    handleEditClick(item) {
      const sel_person = this.ptypes[item.entity_type];
      const sel_fullname = `${item.person.name} ${item.person.lastname}`;
      const sel_type = this.decorated_types[this.i_type].substr(0, 7);
      const sel_inspection = {
        code: item.code,
        description: item.description,
        additional: item.additional,
        inspection_type: this.i_type,
        entity_type: item.entity_type,
        entity_identifier: item.entity_identifier,
        state: item.state,
        created_at: item.created_at
      };
      this.$refs["updatemodal"].showModal(
        sel_type,
        sel_person,
        sel_fullname,
        sel_inspection
      );
    },
    async handleUpdate(inspection) {
      const { data } = await api.update(inspection, inspection.code);
      this.$snack.success(data.message);
      $("#updateInspection").modal("hide");
      this.fetchData();
    },
    handleDeleteClick(item) {
      this.$snack.show({
        text: "¿Esta seguro de eliminar este registro?",
        button: "confirmar",
        action: async () => {
          const { data } = await api.destroy(item.code);
          this.$snack.success(data.message);
          this.inspections.splice(this.inspections.indexOf(item), 1);
        }
      });
    }
  }
};
</script>