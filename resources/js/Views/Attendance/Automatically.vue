<template>
  <section class="card left-panel" id="automatically">
    <div class="card-body">
      <div
        class="card-header d-flex justify-content-between align-items-center"
      >
        <h4 class="card-title">
          {{ currentTime }} -
          <small class="text-muted">{{ subtitle }}</small>
        </h4>
        <m-button
          class="btn-rounded btn-sm"
          :color="isStudent ? 'btn-inverse-secondary' : 'btn-inverse-primary'"
          @pum="isStudent = !isStudent"
        >
          {{ isStudent ? "Estudiante" : "Docente" }}
        </m-button>
      </div>
      <div class="row">
        <div class="col-md-4">
          <vue-qr-reader
            v-show="is_qr"
            :responsive="true"
            :stop-on-scanned="false"
            v-on:code-scanned="onDecode"
            v-on:error-captured="errorCaptured"
          />
          <input
            v-if="!is_qr"
            id="barcodeinput"
            minlength="8"
            maxlength="8"
            class="form-control"
            placeholder="DNI"
            @keypress.enter="handleInputEnter"
          />
          <m-check
            class="ml-1"
            v-model="is_qr"
            id="reader_type"
            text="Escaner qr"
          />
        </div>
        <div class="col-md-8 text-center space-b">
          <blobs v-if="state === 'l'"></blobs>
          <template v-else-if="state === 's'">
            <img
              :src="`/default/${person.profile.image}`"
              alt="Alumno"
              class="rounded-circle"
              style="width: 100px; height: 100px"
            />
            <h5 class="text-primary">
              {{ `${person.name} ${person.lastname}` }}
            </h5>
            <p class="text-secondary">
              <b>{{ cycle }}</b>
            </p>
          </template>
          <span style="font-size: 5rem" :class="states[state]"></span>
          <p
            class="font-weight-bold"
            :class="{ 'text-success': state === 's' }"
          >
            {{ message }}
          </p>
        </div>
      </div>
    </div>
    <div class="card-footer text-center">
      <m-button
        v-show="isStudent"
        @pum="handleChangePriority"
        color="btn-inverse-info"
      >
        <b>i</b>({{ priority }})
      </m-button>
      <m-button @pum="showFinder" color="btn-inverse-warning">
        Sin carnet
      </m-button>
    </div>
  </section>
</template>
<script>
import api from "@/Api/attendance";
import Blobs from "@/Components/Ui/Loader/Blobs";
import { EventBus } from "@/Helpers/bus";
import VueQrReader from "vue-qr-reader/dist/lib/vue-qr-reader.umd.js";
import { errorQr } from "@/Mixins/attendance";
import { dformat } from "@/Helpers/day";
export default {
  mixins: [errorQr],
  components: {
    Blobs,
    VueQrReader
  },
  data() {
    return {
      register: {
        section_code: ""
      },
      person: {},
      cycle: "",
      priority: 1,
      isStudent: true,
      is_qr: true
    };
  },
  created() {
    this.currentTime = dformat(new Date(), "h:mm:ss A");
    setInterval(() => this.updateCurrentTime(), 1000);
    EventBus.$on("afterSelectPerson", this.afterEvent);
  },
  methods: {
    afterEvent({ dni }) {
      $("#finderModal").modal("hide");
      this.save(dni);
    },
    handleInputEnter(event) {
      const { value } = event.target;
      if (value && /^[0-9]{8,8}$/.test(value)) {
        this.save(value);
        setTimeout(() => {
          document.getElementById("barcodeinput").value = "";
        }, 1000);
      }
    },
    handleChangePriority() {
      this.priority = this.priority < 3 ? this.priority + 1 : 1;
      this.$snack.show(`Ingreso Nro ${this.priority} ha sido seleccionado`);
    },
    updateCurrentTime() {
      this.currentTime = dformat(new Date(), "h:mm:ss A");
    },
    showFinder() {
      const who = this.isStudent ? "student" : "teacher";
      this.$store.dispatch("updateSfmOption", {
        who,
        mode: 2,
        after: () => {
          EventBus.$emit("showFinderModal");
        }
      });
    },
    async save(dni) {
      this.state = "l";
      api
        .auto({
          dni,
          type: this.isStudent ? "s" : "t",
          priority: this.priority
        })
        .then(({ data }) => {
          this.message = data.message;
          if (this.isStudent && data.register !== null) {
            const { section_code, level } = data.register;
            this.cycle = `${section_code.substr(-2)} de ${level}`;
          } else {
            this.cycle = `Docente ${new Date().getFullYear()}`;
          }
          this.person = data.person;
          if (!data.person.profile) {
            this.person.profile = {
              image: "deleted.png"
            };
          }
          this.state = "s";
          new Audio(`media/${data.status}`).play();
          this.ocultar();
        })
        .catch((error) => {
          this.message = error.data.message;
          this.state = "e";
          new Audio("media/error.mp3").play();
          this.ocultar();
        });
    },
    ocultar() {
      setTimeout(() => {
        this.state = "n";
        this.message = "";
      }, 3000);
    }
  },
  beforeDestroy() {
    EventBus.$off("afterSelectPerson", this.afterEvent);
  }
};
</script>
