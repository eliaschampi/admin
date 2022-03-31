<template>
  <div class="card text-center profile-card" style="max-width: 20rem">
    <img alt="Portada" src="/img/bg.png" />
    <div class="avatar mx-auto">
      <img :src="`/default/${user.image}`" class="rounded-circle img-fluid" />
      <m-button
        v-if="isAdmin"
        @pum="edit"
        color="btn-rounded btn-primary btn-icon"
      >
        <i class="icon ion-md-create"></i>
      </m-button>
    </div>
    <div class="card-body pt-1">
      <div class="card-title">
        {{ `${user.name} ${user.lastname}` }}
      </div>
      <div class="switch">
        <input
          type="checkbox"
          :disabled="!isAdmin"
          @input="
            $emit('changestate', {
              code: user.code,
              state: $event.target.checked
            })
          "
          :id="'tg' + user.dni"
          :checked="user.state"
        />
        <label :for="'tg' + user.dni" class="success">Toggle</label>
      </div>
      <hr />
      <div><span class="font-weight-bold">Rol:</span> {{ role }}</div>
      <div class="mb-2">
        <span class="font-weight-bold">Sede:</span> {{ user.branch.name }}
      </div>

      <template v-if="$store.state.user.user.code === user.code">
        <m-button @pum="profile" class="btn-rounded btn-icon">
          <i class="icon ion-md-person"></i>
        </m-button>
      </template>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {};
      }
    },
    role: {
      type: String,
      default: ""
    }
  },
  computed: {
    ...mapState(["resources"]),
    isAdmin() {
      return this.$store.state.user.user.rol_code === "A";
    }
  },
  methods: {
    profile() {
      this.$router.push({
        name: "user_profile",
        params: { code: this.user.code }
      });
    },
    edit() {
      this.$router.push({
        name: "update_user",
        params: { code: this.user.code }
      });
    }
  }
};
</script>
