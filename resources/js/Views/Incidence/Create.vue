<template>
  <m-form
    :load="load"
    @save="save"
    :btn-name="isedit ? 'Modificar' : 'Guardar'"
    :title="`${isedit ? 'Modificar' : 'Registrar una'} incidencia`"
  >
    <div class="form-row">
      <div class="col-md-6">
        <m-input
          id="Titulo"
          v-model="incidence.title"
          v-validate="'required|min:5|max:100'"
          label="Incidencia (100 Caracteres)"
          :error="errors.first('Titulo')"
        />
        <m-textarea
          id="desarrollo"
          v-model="incidence.description"
          v-validate="'required|min:10|max:500'"
          :error="errors.first('desarrollo')"
        >
          Desarrollo:
          <i class="text-small">
            ({{ 500 - incidence.description.length }} restantes)
          </i>
        </m-textarea>
        <m-textarea
          id="acuerdo"
          v-model="incidence.agreement"
          v-validate="'required|min:10|max:300'"
          :error="errors.first('acuerdo')"
        >
          Conclusion y acuerdos
          <i class="text-small">
            ({{ 300 - incidence.agreement.length }} restantes)
          </i>
        </m-textarea>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="datess">Fecha de incidencia</label>
          <input
            id="datess"
            type="datetime-local"
            class="form-control"
            v-model="incidence.created_at"
          />
        </div>
        <div class="form-group">
          <label for="instype">Tipo de incidencia</label>
          <select class="form-control" id="instype" v-model="incidence.type">
            <option value="in">Incidencia fisica</option>
            <option value="ve">Incidencia verbal</option>
            <option value="co">Incidencia por mala conducta</option>
            <option value="me">Incidencia médica</option>
            <option value="re">Requisa</option>
            <option value="ot">Otro</option>
          </select>
        </div>
        <template v-if="!isedit">
          <file
            v-model="ifiles"
            accept="image/jpeg,image/png,application/pdf"
            v-validate="'ext:png,pdf,jpg|size:4096'"
            placeholder="Archivo Justificante (imagen o pdf 4mb)"
            :error="errors.first('adjunto')"
          />
          <hr />
          <m-button v-if="!isedit" size="btn-sm" @pum="showModal">
            Agregar Estudiante
          </m-button>
        </template>
        <div class="form-group" v-else>
          <p
            @click="downloadA"
            class="text-primary font-weight-bold pointer"
            v-show="incidence.image_attached"
          >
            <i class="icon ion-md-cloud-download icon-sm text-accent"></i>
            Hay un archivo adjunto aquí.
          </p>
        </div>
        <ul class="list-group">
          <li
            :key="index"
            class="list-group-item text-primary"
            v-for="(item, index) in incidence.students"
          >
            {{ `${item.person.name} ${item.person.lastname}` }}
            <span
              v-if="!isedit"
              @click="quitS(item)"
              class="pull-right text-danger pointer mt-1"
            >
              <i class="icon ion-md-trash icon-sm"></i>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </m-form>
</template>
<script>
import File from "../../Components/Ui/File";
import api from "../../Api/incidence";
import { EventBus } from "../../Helpers/bus";
import cache from "../../Helpers/cache";
import day from "../../Helpers/day";
export default {
  components: {
    File
  },
  data() {
    return {
      load: false,
      incidence: {
        type: "in",
        description: "",
        agreement: "",
        students: [],
        created_at: ""
      },
      ifiles: []
    };
  },
  mounted() {
    EventBus.$on("afterSelectPerson", this.addS);
    let now = day();
    if (this.isedit && cache.hasThis("incidence")) {
      const ins = cache.getItem("incidence");
      //eslint-disable-next-line
      if (ins.code == this.$route.params.code) {
        this.incidence = ins;
        now = day(ins.created_at);
      }
    }
    this.incidence.created_at = now.format("YYYY-MM-DDTHH:mm");
  },
  computed: {
    isedit() {
      return this.$route.params.code;
    }
  },
  methods: {
    showModal() {
      this.$store.dispatch("updateSfmOption", {
        who: "student",
        mode: 2,
        after: () => {
          EventBus.$emit("showFinderModal");
        }
      });
    },
    addS({ name, lastname, dni }) {
      if (!this.incidence.students.some((item) => item.dni === dni)) {
        this.incidence.students.push({
          dni,
          person: {
            name,
            lastname
          }
        });
        $("#finderModal").modal("hide");
      } else {
        this.$snack.warning("Ya esta agregado");
      }
    },
    quitS(item) {
      this.incidence.students.splice(this.incidence.students.indexOf(item), 1);
    },
    storeData() {
      if (this.isedit) {
        return api.update(this.incidence);
      }
      const formData = new FormData();
      formData.append("data", JSON.stringify(this.incidence));
      formData.append("file", this.ifiles[0]);
      return api.store(formData);
    },
    save() {
      if (!this.incidence.students.length) {
        this.$snack.warning("Agregue estudiantes");
        return;
      }
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.load = true;
          this.storeData()
            .then((r) => {
              this.$snack.success(r.data.message);
              this.$router.push({
                name: "main_incidence"
              });
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    },
    async downloadA() {
      const { data } = await api.downloadAttached(this.incidence.code);
      this.$downl(data, this.incidence.image_attached, "");
    }
  },
  beforeDestroy() {
    EventBus.$off("afterSelectPerson", this.addS);
  }
};
</script>
