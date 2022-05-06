<template>
  <div id="users">
    <section class="row mt-4">
      <router-link
        v-can="'A'"
        class="btn btn-rounded btn-accent fixed-action-btn"
        :to="{ name: 'new_user' }"
      >
        <i class="icon ion-ios-add icon-md"></i>
      </router-link>
      <div class="col-md-4">
        <template v-for="(item, index) in roles">
          <div class="card mb-4" :key="index">
            <div class="card-body row">
              <div class="col-md-3 d-flex">
                <i
                  class="icon ion-ios-people text-muted m-auto"
                  style="font-size: 4rem"
                ></i>
              </div>
              <div class="col-md-9">
                <h5 class="mb-1">{{ item.name }}</h5>
                <span class="small">
                  {{ item.description }}
                </span>
                <hr class="mb-1" />
                <p class="text-accent">Usuarios: {{ item.users_count }}</p>
              </div>
            </div>
          </div>
        </template>
        <div class="card">
          <div class="card-body">
            <div class="font-weight-bold text-danger">Usuarios inactivos</div>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <m-table
                  :columns="['Usuario', 'Rol', 'Sede', 'Acciones']"
                  :data="inactives"
                  :head="false"
                >
                  <template slot="data">
                    <tr :key="item.code" v-for="item in inactives">
                      <td>
                        <router-link
                          :to="{
                            name: 'update_user',
                            params: { code: item.code }
                          }"
                        >
                          {{ item.name }}...
                        </router-link>
                      </td>
                      <td>{{ item.rol.name }}</td>
                      <td>{{ item.branch.name }}</td>
                      <td>
                        <b
                          v-can="'A'"
                          class="text-success pointer"
                          @click="
                            changeState({
                              code: item.code,
                              state: true
                            })
                          "
                        >
                          Activar
                        </b>
                      </td>
                    </tr>
                  </template>
                </m-table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="actives.length" class="col-md-8 mygrid g18 h-100">
        <template v-for="item in actives">
          <user-card
            :key="item.code"
            :role="item.rol.name"
            :user="item"
            @changestate="changeState"
          />
        </template>
      </div>
    </section>
  </div>
</template>
<script>
import UserCard from "@/Components/Views/UserCard";
import { mapActions, mapGetters, mapState } from "vuex";
export default {
  components: {
    UserCard
  },
  created() {
    this.fetchRoles();
    this.fetchAll();
  },
  computed: {
    ...mapState("user", ["roles"]),
    ...mapGetters("user", ["actives", "inactives"])
  },
  methods: {
    ...mapActions("user", ["fetchAll", "fetchRoles", "changeState"])
  }
};
</script>
