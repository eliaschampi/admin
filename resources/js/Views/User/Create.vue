<template>
  <form-tab :btnName="btnName" :load="load" @save="save">
    <template slot="tabs">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#main" role="tab">
          <i class="icon ion-md-person d-md-none"></i>
          <span class="d-none d-md-block d-lg-block">General</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#contact" role="tab">
          <i class="icon ion-md-call d-md-none"></i>
          <span class="d-none d-md-block d-lg-block">Contacto</span>
        </a>
      </li>
    </template>
    <div class="tab-pane fade in show active" id="main" role="tabpanel">
      <div class="card-body">
        <div class="row">
          <m-input
            class="col-md-6"
            id="Nombre"
            v-model="user.name"
            v-validate="'required|min:3|max:50'"
            :error="errors.first('Nombre')"
          />
          <m-input
            class="col-md-6"
            id="Apellidos"
            v-model="user.lastname"
            v-validate="'required|min:5|max:80'"
            :error="errors.first('Apellidos')"
          />
        </div>
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="DNI"
            v-model="user.dni"
            v-validate="'required|numeric|min:8|max:8'"
            :error="errors.first('DNI')"
          />
          <div class="form-group col-md-6">
            <label for="sex1id">Sexo</label>
            <select
              class="form-control"
              id="sex1id"
              name="sexo"
              v-model="user.gender"
            >
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>
        </div>
        <div class="form-row" v-can="'A'">
          <div class="form-group col-md-6">
            <label for="rolselec">Rol en el sistema</label>
            <select
              class="form-control"
              id="rolselec"
              name="rol"
              v-validate="'required'"
              v-model="user.rol_code"
            >
              <option :key="item.code" v-for="item in roles" :value="item.code">
                {{ item.name }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('rol')">
              {{ errors.first("rol") }}
            </span>
          </div>
          <div class="form-group col-md-6" v-if="option === 'new_user'">
            <label for="sagre">Sede</label>
            <select
              class="form-control"
              id="sagre"
              name="sede"
              v-validate="'required'"
              v-model="user.branch_code"
            >
              <option
                :key="item.code"
                v-for="item in branches"
                :value="item.code"
              >
                {{ item.name }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('sede')">
              {{ errors.first("sede") }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel">
      <div class="card-body">
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="Correo"
            v-model="user.email"
            v-validate="'required|email|min:6|max:50'"
            :error="errors.first('Correo')"
          />
          <m-input
            class="col-md-6"
            id="Celular"
            v-model="user.phone"
            v-validate="'numeric|min:9|max:9'"
            :error="errors.first('Celular')"
          />
        </div>
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="Direccion"
            v-model="user.address"
            v-validate="'required|min:3|max:150'"
            :error="errors.first('Direccion')"
          />
        </div>
      </div>
    </div>
  </form-tab>
</template>

<script>
import { mapActions, mapState } from "vuex";
import apiBranch from "@/Api/branch";
import api from "@/Api/user";
import FormTab from "@/Components/Card/FormTab";
export default {
  components: {
    FormTab
  },
  data() {
    return {
      title: "Registrar un nuevo",
      btnName: "Guardar Registro",
      option: "",
      load: false,
      branches: [],
      user: {
        gender: "M"
      }
    };
  },
  created() {
    this.fetchRoles();
    apiBranch.fetchAll().then((r) => {
      this.branches = r.data.values;
    });
    this.option = this.$route.name;
    if (this.option === "update_user") {
      api
        .fetch(this.route_code)
        .then((r) => {
          this.user = r.data.user;
          this.title = "Modificar";
          this.btnName = "Guardar Cambios";
        })
        .catch(() => {
          this.$router.push({ name: "not_found" });
        });
    }
  },
  computed: {
    ...mapState("user", {
      roles: (state) => state.roles
    }),
    route_code() {
      return this.$route.params.code;
    }
  },
  methods: {
    ...mapActions("user", ["fetch", "fetchRoles", "set", "update"]),
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.load = true;
          this.store(this.user)
            .then((r) => {
              this.$snack.success(r);
              this.$router.push({ name: "home" });
            })
            .catch((error) => {
              if (error.code === 422) {
                this.$setErrors(error.data);
              }
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    },
    store(user) {
      if (this.option === "new_user") {
        const { rol_code, branch_code, gender } = user;
        const ramdom = Math.floor(Math.random() * 9) + 1;
        const year = new Date().getFullYear().toString().substr(-2);
        user.code = `US${branch_code}${rol_code}-${gender}A${year}${ramdom}`;
        return this.set(user);
      } else {
        return this.update(user);
      }
    }
  }
};
</script>
