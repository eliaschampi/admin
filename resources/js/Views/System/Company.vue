<template>
  <section class="section">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <panel title="Subir Documentos">
          <div class="text-center">
            <img
              src="/default/logo.png"
              alt="Logo"
              class="mb-3 mx-auto"
              width="50%"
            />
            <p class="text-muted">
              <small>Formato png, max 1mb proporcional</small>
            </p>
            <label class="btn btn-accent btn-sm btn-rounded" v-can="'A'">
              <span>Subir Logo</span>
              <input
                @change="upload"
                accept="image/png"
                class="d-none"
                id="logo"
                ref="logo"
                type="file"
              />
            </label>
          </div>
          <hr />
          <div v-if="can('A')" class="d-flex flex-wrap justify-content-around">
            <div class="section-preview text-center mr-2 p-3">
              <label for="fcard" class="text-primary pointer">
                plantilla de carnet
                <div class="icon ion-md-cloud-upload icon-md"></div>
                <input
                  @change="upload('card')"
                  accept="image/png"
                  class="d-none"
                  id="fcard"
                  ref="card"
                  type="file"
                />
              </label>
            </div>
            <div class="section-preview text-center p-3">
              <label for="pcard" class="text-primary pointer">
                plantilla de reportes pdf
                <div class="icon ion-md-cloud-upload icon-md"></div>
                <input
                  @change="upload('pdf')"
                  accept="image/png"
                  class="d-none"
                  id="pcard"
                  ref="card"
                  type="file"
                />
              </label>
            </div>
          </div>
        </panel>
      </div>
      <div class="col-lg-8 mb-4">
        <form @submit.prevent="save">
          <card title="Información de la Institución" :f="can('A')">
            <div class="row">
              <div :key="item.code" class="col-md-6" v-for="item in company">
                <m-input
                  :disabled="!can('A')"
                  :id="item.code"
                  v-model="item.value"
                  :label="item.name"
                />
              </div>
            </div>
            <div slot="foot" class="text-center">
              <m-submit :load="load">Actualizar Información </m-submit>
            </div>
          </card>
        </form>
      </div>
    </div>
  </section>
</template>
<script>
import api from "@/Api/main";
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      company: [],
      load: false
    };
  },
  created() {
    this.get();
  },
  computed: mapGetters(["can"]),
  methods: {
    get() {
      api.fetchByTags("cpny").then((r) => {
        this.company = r.data.configs;
      });
    },
    upload($event, name = "logo") {
      this.$snack.show({
        text: `Está a punto de subir nueva imagen [${name}]`,
        button: "CONTINUAR",
        action: () => {
          const file = this.$refs[name].files[0];
          const reader = new FileReader();
          if (file) {
            if (/\.(png)$/i.test(file.name)) {
              reader.readAsDataURL(file);
              const formData = new FormData();
              formData.set("image", file);
              formData.append("name", name);
              api.upload(formData).then((r) => {
                this.$snack.success(r.data);
                this.$router.go(0);
              });
            } else {
              this.$snack.warning("Archivo Invalido");
            }
          }
        }
      });
    },
    save() {
      this.$snack.show({
        text: "¿La información del colegio será modificado?",
        button: "Continuar",
        action: () => {
          this.load = true;
          api
            .setCompany({
              data: this.company
            })
            .then((r) => {
              this.$snack.success(r.data);
              this.get();
              this.load = false;
            });
        }
      });
    }
  }
};
</script>
