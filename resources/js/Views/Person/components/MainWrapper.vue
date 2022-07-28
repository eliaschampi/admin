<template>
  <div class="row">
    <div class="col-lg-4 col-md-5 col-xlg-3">
      <div class="card profile-card mb-5" v-if="store_pid">
        <img alt="Portada" :src="`/default/user-info${branch_code}.jpg`" />
        <div class="avatar mx-auto">
          <img :src="image" alt="avatar" class="rounded-circle" />
          <m-button
            v-if="uploable"
            data-toggle="modal"
            data-target="#foto"
            color="btn-rounded btn-accent btn-icon"
            icon="icon ion-md-cloud-upload"
          >
          </m-button>
        </div>
        <div class="card-body text-center">
          <div class="card-title">
            {{ fullname }}
          </div>
          <slot name="profile-info" />
        </div>
        <div class="card-footer text-center">
          <slot name="profile-foot">
            <m-button
              icon="icon ion-md-cloud-download"
              color="btn-light"
              size="btn-sm"
              @pum="$emit('dw_fu')"
            >
              Descargar F.U.
            </m-button>
          </slot>
        </div>
      </div>
      <slot name="room">
        <aeduca :ptype="ptype" />
      </slot>
    </div>
    <div class="col-lg-8 col-md-7 col-xlg-9 mt-5 mt-md-0">
      <slot />
    </div>
    <modal
      id="foto"
      title="Subir Foto"
      btn-name="subir"
      @sub="save"
      @closed="cancelImage"
    >
      <div class="alert alert-warning">
        Elija una foto cuadrada de tamaño pasaporte, (peso maximo 500kb)
      </div>
      <file
        id="userimage"
        @input="upload"
        v-model="images"
        accept="image/jpeg,image/png"
        v-validate="'ext:png,jpg|size:512'"
        :error="errors.first('userimage')"
      />
      <div class="avatar white mt-0 d-flex" v-if="imagePreview">
        <img
          :src="imagePreview"
          class="rounded-circle mx-auto"
          width="100"
          height="100"
        />
      </div>
    </modal>
  </div>
</template>
<script>
import request from "../../../Http";
import File from "../../../Components/Ui/File.vue";
import Aeduca from "./Aeduca.vue";
export default {
  name: "MainWrapper",
  components: { File, Aeduca },
  props: {
    ptype: {
      type: String,
      default: "user"
    },
    pid: String
  },
  data() {
    return {
      images: [],
      imagePreview: ""
    };
  },
  computed: {
    fullname() {
      return this.$store.getters[`${this.ptype}/fullname`];
    },
    image() {
      return this.$store.getters[`${this.ptype}/image`];
    },
    store_pid() {
      return this.pid || this.$store.getters[`${this.ptype}/dni`];
    },
    uploable() {
      if (this.$route.name === "user_profile") {
        return true;
      }
      return this.$store.getters["can"]("AS");
    },
    branch_code() {
      return this.$store.getters["user/branch_code"];
    }
  },
  methods: {
    upload(images) {
      const file = images[0];
      const reader = new FileReader();
      reader.onload = function (e) {
        this.imagePreview = e.target.result;
      }.bind(this);
      if (file) {
        if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
          reader.readAsDataURL(file);
        }
      }
    },
    save() {
      const { ptype, store_pid } = this;
      let t = ptype;
      if (["student", "teacher"].includes(ptype)) {
        t = "profile";
      } else if (ptype === "user") {
        t = "usuario";
      } else {
        return;
      }
      const formData = new FormData();
      formData.append("image", this.images[0]);
      formData.append("_method", "PUT");
      request
        .post(`/${t}/image/${store_pid}`, formData, {
          "Content-Type": "multipart/form-data"
        })
        .then(({ data }) => {
          this.$snack.success(data.message);
          this.cancelImage();
          this.$emit("img_uploaded", data.filename);
          $("#foto").modal("hide");
        });
    },
    cancelImage() {
      this.images = [];
      this.imagePreview = "";
    }
  }
};
</script>
