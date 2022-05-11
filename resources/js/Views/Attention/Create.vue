<template>
  <m-form
    :load="load"
    @save="save"
    :btn-name="isedit ? 'Modificar' : 'Guardar'"
    :title="`${isedit ? 'Modificar' : 'Registrar una'} atención`"
  >
    <div class="form-row">
      <div class="col-md-6">
        <m-input
          id="Titulo"
          v-model="attention.title"
          v-validate="'required|min:5|max:80'"
          label="Motivo (80 Caracteres)"
          placeholder="Ingrese aquí el motivo de la antención o entrevista"
          :error="errors.first('Titulo')"
        />
        <m-textarea
          id="Introduccion"
          v-model="attention.introduction"
          v-validate="'required|min:10|max:300'"
          :error="errors.first('Introduccion')"
          size="3"
        >
          El entrevistador compartió
          <i class="text-small">
            ({{ 300 - attention.introduction.length }} restantes)
          </i>
        </m-textarea>
        <m-textarea
          id="Desarrollo"
          v-model="attention.description"
          v-validate="'required|min:10|max:500'"
          :error="errors.first('Desarrollo')"
        >
          El
          {{
            attention.person_type === "student"
              ? "estudiante"
              : attention.person_type === "family"
              ? "apoderado"
              : "docente"
          }}
          compartió
          <i class="text-small">
            ({{ 500 - attention.description.length }} restantes)
          </i>
        </m-textarea>
        <m-textarea
          id="Acuerdos"
          v-model="attention.conclusion"
          v-validate="'required|min:10|max:200'"
          size="3"
          :error="errors.first('Acuerdos')"
        >
          Acuerdos
          <i class="text-small">
            ({{ 200 - attention.conclusion.length }} restantes)
          </i>
        </m-textarea>
      </div>
      <div class="col-md-6">
        <div class="selectgroup selectgroup-pills mb-2">
          <label class="selectgroup-item">
            <input
              type="radio"
              name="a_type"
              value="p"
              class="selectgroup-input"
              v-model="attention.type"
            />
            <span class="selectgroup-button">Presencial</span>
          </label>
          <label class="selectgroup-item">
            <input
              type="radio"
              name="a_type"
              value="v"
              class="selectgroup-input"
              v-model="attention.type"
            />
            <span class="selectgroup-button">Virtual</span>
          </label>
        </div>
        <hr />
        <div class="form-group">
          <label for="datess">Fecha de atención</label>
          <input
            id="datess"
            type="datetime-local"
            class="form-control"
            v-model="attention.created_at"
          />
        </div>
        <file
          v-if="!isedit"
          v-model="files"
          accept="image/jpeg,image/png,application/pdf"
          v-validate="'ext:png,jpg,pdf|size:4096'"
          placeholder="Documento Justificante (Imagen o pdf max 4mb)"
          :error="errors.first('adjunto')"
        />
        <div
          @click="downloadA"
          class="form-group text-primary font-weight-bold pointer"
          v-else-if="attention.file_attached"
        >
          <i class="icon ion-md-cloud-download icon-sm text-accent"></i>
          Hay un documento adjunto aquí.
        </div>
        <hr />
        <div class="mb-1">Seleccione una opción</div>
        <person-type v-model="attention.person_type" />
        <input-finder :fullname="person_name" :who="attention.person_type" />
      </div>
    </div>
  </m-form>
</template>
<script>
import api from "../../Api/attention";
import InputFinder from "../../Components/Finder/InputFinder.vue";
import File from "../../Components/Ui/File";
import PersonType from "../../Components/Views/PersonType.vue";
import { EventBus } from "../../Helpers/bus";
import cache from "../../Helpers/cache";
import day from "../../Helpers/day";
import { simpledmax } from "../../Mixins/utils";
export default {
  mixins: [simpledmax],
  components: {
    InputFinder,
    File,
    PersonType
  },
  data() {
    return {
      load: false,
      attention: {
        type: "p",
        person_type: "student",
        person_dni: null,
        introduction: "",
        description: "",
        conclusion: "",
        created_at: ""
      },
      files: [],
      person_name: ""
    };
  },
  computed: {
    isedit() {
      return this.$route.params.code;
    }
  },
  watch: {
    "attention.person_type"() {
      this.person_name = "";
      this.attention.person_dni = null;
    }
  },
  mounted() {
    EventBus.$on("afterSelectPerson", this.addPerson);
    let now = day();
    if (this.isedit && cache.hasThis("attention")) {
      const att = cache.getItem("attention");
      //eslint-disable-next-line
      if (this.isedit == att.code) {
        this.attention = att;
        now = day(att.created_at);
        this.person_name = `${att.person.name} ${att.person.lastname}`;
      }
    }
    this.attention.created_at = now.format("YYYY-MM-DDTHH:mm");
  },
  methods: {
    addPerson(person) {
      this.person_name = `${person.name} ${person.lastname}`;
      this.attention.person_dni = person.dni;
      $("#finderModal").modal("hide");
    },
    storeData() {
      if (this.isedit) {
        return api.update(this.attention);
      }
      const formData = new FormData();
      formData.append("data", JSON.stringify(this.attention));
      formData.append("file", this.files[0]);
      return api.store(formData);
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r && this.attention.person_dni && this.attention.created_at) {
          this.load = true;
          this.storeData()
            .then((r) => {
              this.$snack.success(r.data.message);
              this.$router.push({
                name: "main_attention"
              });
            })
            .finally(() => {
              this.load = false;
            });
        } else {
          this.$snack.warning("Algunos campos aún estan vacios");
        }
      });
    },
    async downloadA() {
      const { data } = await api.downloadAttached(this.attention.code);
      this.$downl(data, this.attention.file_attached, "");
    }
  },
  beforeDestroy() {
    EventBus.$off("afterSelectPerson", this.addPerson);
  }
};
</script>
