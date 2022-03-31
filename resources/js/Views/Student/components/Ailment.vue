<template>
  <div class="mt-2">
    <m-button
      v-can="'S'"
      icon="icon ion-md-add"
      size="btn-sm"
      data-toggle="modal"
      data-target="#amodal"
      color="btn-inverse-primary"
    >
      Agregar
    </m-button>
    <m-table :columns="columns" :data="ailments" :head="false">
      <template slot="data">
        <tr :key="item.code" v-for="item in ailments">
          <td>{{ item.code }}</td>
          <td>{{ types[item.type].name }}</td>
          <td>{{ item.ailment }}</td>
          <td>
            <m-action @action="edit(item)" />
            <m-action
              @action="manageAdj(item)"
              :icon="item.attached ? 'cloud-download' : 'cloud-upload'"
              color="grey"
              tool="Adjunto"
            />
            <m-action
              v-can="'N'"
              @action="del(item)"
              icon="trash"
              color="danger"
              tool="Eliminar"
            />
          </td>
        </tr>
      </template>
    </m-table>

    <modal
      id="amodal"
      :btnName="btnName"
      @sub="save"
      title="Agregar un nuevo registro"
      @closed="onmClose"
      :disabled="!canAn"
    >
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="aefwe">Tipo</label>
          <select
            class="form-control"
            id="aefwe"
            name="tipo"
            v-validate="'required'"
            v-model="ailment.type"
          >
            <option :key="item.code" v-for="item in types" :value="item.code">
              {{ item.name }}
            </option>
          </select>
          <span class="small text-danger" v-show="errors.has('tipo')">
            {{ errors.first("tipo") }}
          </span>
        </div>
        <m-input
          class="col-md-12"
          id="Nombre"
          label="Descripción"
          v-model="ailment.ailment"
          v-validate="'required|min:4|max:100'"
          :error="errors.first('Nombre')"
        />
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <m-check
            id="hasCT"
            text="Tiene Causa y Tratamiento."
            v-model="hasCT"
          />
        </div>
      </div>
      <div class="form-row" v-show="hasCT">
        <m-textarea
          class="col-md-6"
          id="Causa"
          size="3"
          label="Causa"
          v-model="ailment.cause"
          v-validate="{ required: hasCT, min: 5, max: 200 }"
          :error="errors.first('Causa')"
        />
        <m-textarea
          class="col-md-6"
          id="Tratamiento"
          size="3"
          label="Tratamiento"
          v-model="ailment.treatment"
          v-validate="{ required: hasCT, min: 5, max: 200 }"
          :error="errors.first('Tratamiento')"
        />
      </div>
    </modal>
    <attached @onsave="upAdj" :code="code" />
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import api from "@/Api/ailment";
import types from "@/Data/ailmentTypes.json";
import Attached from "./Attached";
import { fetchData } from "@/Mixins";
export default {
  mixins: [fetchData],
  components: { Attached },
  data() {
    return {
      ailment: {},
      ailments: [],
      btnName: "registrar",
      columns: ["Còdigo", "Tipo", "Descripción", "Acciones"],
      hasCT: false,
      code: "",
      types
    };
  },
  computed: {
    ...mapGetters("student", ["dni"]),
    canAn() {
      return this.$store.getters["can"]("AN");
    }
  },
  methods: {
    onmClose() {
      this.ailment = {};
      this.hasCT = false;
      this.btnName = "registrar";
    },
    fetchData() {
      api.fetchByStudent(this.dni).then((r) => {
        this.ailments = r.data.values;
      });
    },
    edit(item) {
      this.ailment = Object.assign({}, item);
      this.hasCT = item.cause !== null;
      this.btnName = "modificar";
      $("#amodal").modal("show");
    },
    async store(ailment) {
      if (this.btnName === "registrar") {
        ailment.student_dni = this.dni;
        const { data } = await api.set(ailment);
        data.value.attached = "";
        if (!this.hasCT) {
          data.value.cause = null;
          data.value.treatment = null;
        }
        this.ailments.push(data.value);
        return data.message;
      }
      const { data } = await api.update(ailment);
      const item = this.ailments.find((i) => i.code === ailment.code);
      this.ailments.splice(this.ailments.indexOf(item), 1, ailment);
      return data.message;
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.store(this.ailment).then((message) => {
            this.$snack.success(message);
            $("#amodal").modal("hide");
          });
        }
      });
    },
    manageAdj(item) {
      if (item.attached) {
        api.downloadA(item.code).then((r) => {
          this.$downl(r.data, `AM - ${this.dni} - RN ${item.code}`);
        });
      } else {
        this.code = item.code;
        $("#mAttached").modal("show");
      }
    },
    upAdj(formData) {
      api.uploadA(formData).then((r) => {
        this.$snack.success(r.data.message);
        this.fetchData();
        $("#mAttached").modal("hide");
      });
    },
    del(item) {
      this.$snack.show({
        text: this.$confirm("exclude", "registro seleccionado"),
        button: "Confirmar",
        action: () => {
          api.del(item.code).then((r) => {
            this.ailments.splice(this.ailments.indexOf(item), 1);
            this.$snack.success(r.data.message);
          });
        }
      });
    }
  }
};
</script>
<style scoped>
#add {
  top: 2em;
}
</style>
