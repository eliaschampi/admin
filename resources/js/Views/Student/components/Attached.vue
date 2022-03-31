<template>
  <modal
    id="mAttached"
    :title="'Adjunto para registro nro ' + code"
    btn-name="subir"
    @sub="onsave"
  >
    <alert>
      Una vez subido el adjunto, no podrá volver a subir un nuevo archivo
    </alert>
    <file
      id="adjunto"
      v-model="files"
      v-validate="'ext:pdf|size:2048'"
      placeholder="Subir pdf (max 2mb)"
      :error="errors.first('adjunto')"
    />
  </modal>
</template>
<script>
import File from "@/Components/Ui/File";
export default {
  components: { File },
  props: ["code"],
  data() {
    return {
      files: []
    };
  },
  methods: {
    onsave() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          const formData = new FormData();
          formData.append("code", this.code);
          formData.append("attached", this.files[0]);
          this.$emit("onsave", formData, this.code);
          this.files = [0];
        }
      });
    }
  }
};
</script>
