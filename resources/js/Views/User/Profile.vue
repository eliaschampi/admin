<template>
  <main-wrapper :pid="user.code" @img_uploaded="imgUploaded">
    <div slot="profile-info">
      <div class="section-preview mt-4 mx-auto">
        <div class="title">Acceso al sistema</div>
        <div class="switch">
          <input type="checkbox" disabled id="u-state" :checked="user.state" />
          <label for="u-state" class="accent">Toggle</label>
        </div>
      </div>
    </div>
    <template slot="profile-foot">
      <m-button
        v-can="'APY'"
        color="btn-inverse-primary"
        @pum="reAuthenticate"
        size="btn-sm"
      >
        Acceder a otra sede
      </m-button>
    </template>
    <form v-can="'NPS'" slot="room" @submit.prevent="savePassword">
      <panel title="Seguridad" :f="true">
        <m-input
          id="Contraseña"
          type="password"
          v-model="pass.current_password"
          label="Contraseña Actual"
          v-validate="'required|min:5|max:50'"
          :error="errors.first('Contraseña')"
        >
        </m-input>
        <m-input
          id="Nuevo"
          v-model="pass.password"
          label="Nueva Contraseña"
          v-validate="'required|min:6|max:50|confirmed:pass'"
          :error="errors.first('Nuevo')"
        >
        </m-input>
        <m-input
          id="password_confirmation"
          type="password"
          ref="pass"
          v-model="pass.password_confirmation"
          label="Repetir la Contraseña"
        >
        </m-input>
        <m-submit slot="foot" size="btn-sm">Guardar</m-submit>
      </panel>
    </form>
    <card title="Datos personales">
      <m-plain label="Sede:" :value="user.branch.name" />
      <m-plain label="Rol:" :value="user.rol.name" />
      <m-plain label="Código:" :value="user.code" />
      <m-plain label="DNI:" :value="user.dni" />
      <m-plain label="Email:" :value="user.email" />
      <m-plain label="Celular:" :value="user.phone" />
      <m-plain label="Dirección:" :value="user.address" />
      <m-router
        slot="foot"
        size="btn-sm"
        v-can="'NSP'"
        :to="{
          name: 'update_user',
          params: { code: user.code }
        }"
      >
        Actualizar datos
      </m-router>
    </card>
    <modal
      @sub="handleChangeBranch"
      id="udmodal"
      title="Cambiar de sede"
      btn-name="Confirmar"
      :disabled="!branch"
    >
      <div class="form-group">
        <label for="braselid">Sede</label>
        <select class="form-control" id="braselid" v-model="branch">
          <option :key="item.code" v-for="item in branches" :value="item">
            {{ item.name }}
          </option>
        </select>
      </div>
    </modal>
  </main-wrapper>
</template>
<script>
import { mapActions, mapState } from "vuex";
import api from "../../Api/user";
import apiBranch from "../../Api/branch";
import Panel from "../../Components/Card/Panel.vue";
import MainWrapper from "../Person/components/MainWrapper.vue";
import { EventBus } from "../../Helpers/bus";
export default {
  components: {
    MainWrapper,
    Panel
  },
  data() {
    return {
      pass: {
        current_password: "",
        password: "",
        password_confirmation: ""
      },
      branches: [],
      branch: null
    };
  },
  mounted() {
    EventBus.$on("reauthed", this.showAdvanceModal);
  },
  computed: {
    ...mapState("user", ["user"]),
    ...mapState(["resources"])
  },
  methods: {
    ...mapActions("user", ["changePassword"]),
    reAuthenticate() {
      EventBus.$emit("checkauth", "Un momento...", "/check");
    },
    showAdvanceModal() {
      if (this.branches.length === 0) {
        apiBranch.fetchAll().then((r) => {
          const lcode = this.user.branch_code;
          this.branches = Object.values(r.data.values).filter(
            (item) => item.code !== lcode
          );
        });
      }
      $("#udmodal").modal("show");
    },
    async handleChangeBranch() {
      const { code, name } = this.branch;
      const { data } = await api.changeBranch(code);
      this.$snack.success(data);
      this.$store.dispatch("updateUserCachedProps", {
        branch_code: code,
        branch: {
          code,
          name
        }
      });
      $("#udmodal").modal("hide");
      setTimeout(() => {
        this.$router.go(0);
      }, 500);
    },
    savePassword() {
      this.$validator.validate().then((r) => {
        if (r) {
          this.$snack.show({
            text: this.$confirm("change", "el contraseña"),
            button: "CONFIRMAR",
            action: () => {
              this.pass.user_code = this.user.code;
              api
                .changePassword(this.pass)
                .then(({ data }) => {
                  this.$snack.success(data);
                })
                .catch((error) => {
                  if (error.code === 500) {
                    this.$snack.danger(error.data.message);
                  } else if (error.code === 422) {
                    this.$setErrors(error.data);
                  }
                });
            }
          });
        }
      });
    },
    imgUploaded(image) {
      this.$store.dispatch("updateUserCachedProps", {
        image
      });
    }
  },
  beforeDestroy() {
    EventBus.$off("reauthed", this.showAdvanceModal);
  }
};
</script>
