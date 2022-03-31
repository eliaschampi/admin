<template>
  <card title="Plataforma Aeduca" :f="false">
    <template v-if="person.profile">
      <div slot="rb" class="button-group">
        <m-button
          size="btn-sm"
          color="btn-inverse-secondary btn-icon"
          icon="icon ion-md-print"
          @pum="printInfo"
        >
        </m-button>
        <m-button
          size="btn-sm"
          color="btn-inverse-danger btn-icon"
          icon="icon ion-ios-remove-circle-outline"
          @pum="deleteProfile"
        >
        </m-button>
      </div>
      <m-plain label="Estado:">
        <i class="icon ion-ios-key icon-sm text-success"></i>
        Habilitado
      </m-plain>
      <m-plain label="Últ. vez en línea:">
        <i class="icon ion-md-time icon-sm text-primary"></i>
        {{ person.profile.last_logout | ago }}
      </m-plain>
      <div class="form-group row">
        <label class="font-weight-bold col-form-label text-accent col-sm-3">
          Contraseña:
        </label>
        <div class="col-sm-9 form-inline">
          <b>{{ showpass ? person.profile.original_password : "*********" }}</b>
          <span class="ml-2 text-success pointer" @click="showpass = !showpass">
            {{ showpass ? "ocultar" : "mostrar" }}
          </span>
          <span class="ml-2 text-info pointer" @click="updatePassword">
            restablecer
          </span>
        </div>
      </div>
      <alert>
        Las <b>contraseñas prestablecidas</b> son generadas por el sistema.
        Tenga en cuenta que el estudiante o docente puede cambiarlo
        posteriormente.
      </alert>
    </template>
    <template v-else>
      <m-button
        slot="rb"
        size="btn-sm"
        color="btn-inverse-success"
        icon="icon ion-md-create"
        @pum="createProfile"
      >
        Habilitar ahora
      </m-button>
      <empty title="Su acceso no esta habilitado" />
    </template>
  </card>
</template>
<script>
import api from "@/Api/person";
import Empty from "@/Components/Lab/Empty.vue";
export default {
  components: { Empty },
  name: "Profile",
  props: {
    ptype: String
  },
  data() {
    return {
      profile: {},
      showpass: false
    };
  },
  computed: {
    person() {
      return this.$store.state[this.ptype][this.ptype];
    }
  },
  methods: {
    canAs(run) {
      if (this.$store.getters["can"]("AS")) {
        run();
      } else {
        this.$snack.show("Lo sentimos, no puedes realizar esta operación");
      }
    },
    createProfile() {
      this.canAs(() => {
        const img = this.person.gender === "M" ? "men" : "women";
        api
          .storeProfile({
            dni: this.person.dni,
            image: `default_${img}.png`
          })
          .then(({ data }) => {
            this.$snack.success(data.message);
            this.$store.commit(`${this.ptype}/UPDATE_PROFILE`, {
              person_dni: this.person.dni,
              last_logout: new Date(),
              original_password: data.original_password,
              image: `default_${img}.png`
            });
          });
      });
    },
    updatePassword() {
      this.canAs(() => {
        this.$snack.show({
          text: "¡Cuidado! Su contraseña actual será restablecido",
          button: "CONFIRMAR",
          action: () => {
            api.updateProfile(this.person.dni).then(({ data }) => {
              this.$snack.success(data.message);
              this.$store.commit(`${this.ptype}/UPDATE_PROFILE`, {
                ...this.person.profile,
                original_password: data.original_password
              });
            });
          }
        });
      });
    },
    deleteProfile() {
      this.canAs(() => {
        this.$snack.show({
          text: "Se eliminará el acceso a la plataforma",
          button: "CONFIRMAR",
          action: () => {
            api.destroyProfile(this.person.dni).then(({ data }) => {
              this.$snack.success(data);
              this.$store.commit(`${this.ptype}/UPDATE_PROFILE`, null);
            });
          }
        });
      });
    },
    printInfo() {
      api.printProfileInfo(this.person.dni).then(({ data }) => {
        this.$downl(
          data,
          this.$store.getters[`${this.ptype}/fullname`],
          ".pdf"
        );
      });
    }
  }
};
</script>
