<template>
  <modal
    @sub="handleSubmit"
    btnName="Guardar Cambios"
    id="jus"
    title="Justificación"
  >
    <m-textarea
      v-validate="'required|min:10|max:100'"
      id="Justificacion"
      v-model="newdescription"
      :error="errors.first('Justificacion')"
    >
      Justificación
    </m-textarea>

    <div class="text-center my-2" v-if="justification">
      <m-button
        v-show="justification.attached"
        color="btn-inverse-primary"
        icon="icon ion-md-cloud-download"
        size="btn-sm"
        @pum="handleDownloadAttached"
      >
        Descargar archivo adjunto
      </m-button>

      <div
        class="selectgroup selectgroup-pills"
        v-show="state === 'envió justificación'"
      >
        <label class="selectgroup-item">
          <input
            type="radio"
            name="accept_jus"
            value="yes"
            class="selectgroup-input"
            v-model="aprove"
          />
          <span class="selectgroup-button">Aceptar</span>
        </label>
        <label class="selectgroup-item">
          <input
            type="radio"
            name="accept_jus"
            value="no"
            class="selectgroup-input"
            v-model="aprove"
          />
          <span class="selectgroup-button">Rechazar</span>
        </label>
      </div>
    </div>
    <template v-else>
      <file
        v-model="files"
        v-validate="'ext:jpg,jpeg,png,pdf|size:2048'"
        placeholder="Doc. justificante 2mb"
        :error="errors.first('adjunto')"
      />
    </template>
  </modal>
</template>
<script>
import File from "@/Components/Ui/File.vue";
import api from "@/Api/justification";
export default {
  components: { File },
  props: {
    code: Number,
    state: String,
    justification: [Object, null]
  },
  data() {
    return {
      files: [],
      aprove: "no",
      newdescription: ""
    };
  },
  watch: {
    justification(val) {
      this.newdescription = val ? val.description : "";
    }
  },
  methods: {
    async handleDownloadAttached() {
      try {
        const { data } = await api.downloadAttached(this.code);
        this.$downl(data, this.justification.attached, "");
      } catch (error) {
        if (error.code === 404) {
          this.$snack.warning("Recurso adjunto no encontrado");
        }
      }
    },
    async handleSubmit() {
      if (this.state === "envió justificación") {
        const { data } = await api.toggle(this.code, this.aprove);
        this.$emit("updated", data);
      } else if (this.state === "falta") {
        this.$validator.validateAll().then(async (r) => {
          if (r) {
            const formData = new FormData();
            formData.append("code", this.code);
            formData.append("description", this.newdescription);
            const file = this.files[0];
            if (file) {
              formData.append("file", file);
            }
            const { data } = await api.store(formData);
            this.$emit("updated", data);
          }
        });
      } else {
        this.$emit("closed");
      }
    }
  }
};
</script>
