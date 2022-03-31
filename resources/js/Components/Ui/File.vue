<template>
  <div class="addon-group">
    <label>{{ placeholder }}</label>
    <div class="input-group col-xs-12">
      <input
        type="text"
        :value="filename"
        class="form-control file-upload-info"
        disabled
        placeholder="Click para seleccionar"
      />
      <span class="input-group-append">
        <label class="btn btn-primary" for="file">
          <input
            @change="upload"
            :accept="accept"
            id="file"
            name="adjunto"
            type="file"
            class="d-none"
          />
          <i class="icon ion-md-cloud-upload icon-sm"></i>
        </label>
      </span>
    </div>
    <span class="small text-danger" v-show="error">{{ error }}</span>
  </div>
</template>
<script>
export default {
  $_veeValidate: {
    name() {
      return "adjunto";
    },
    value() {
      return this.files;
    }
  },
  model: {
    prop: "files",
    event: "input"
  },
  props: {
    files: {
      type: Array,
      default: () => []
    },
    error: {
      type: String,
      default: ""
    },
    accept: {
      type: String,
      default: "application/pdf"
    },
    placeholder: {
      type: String,
      default: "Subir un archivo"
    }
  },
  computed: {
    filename() {
      if (this.files.length) {
        return this.files[0].name;
      }
      return "Selecciona un archivo";
    }
  },
  methods: {
    upload(e) {
      this.$emit("input", Array.from(e.target.files));
    }
  }
};
</script>
