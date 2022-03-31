<template>
  <div id="createperson">
    <form-tab
      :btnName="$route.params.dni ? 'Modificar' : 'Guardar'"
      :load="load"
      @save="save"
    >
      <template slot="tabs">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#main" role="tab">
            <i class="icon ion-md-person d-md-none"></i>
            <span class="d-none d-md-block d-lg-block"> Datos Personales </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#additional" role="tab">
            <i class="icon ion-md-information-circle-outline d-md-none"></i>
            <span class="d-none d-md-block d-lg-block">
              Información Adicional
            </span>
          </a>
        </li>
      </template>
      <div class="tab-pane fade show active" id="main" role="tabpanel">
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="Nombre"
            v-model="person.name"
            v-validate="'required|min:3|max:50'"
            :error="errors.first('Nombre')"
          />
          <m-input
            class="col-md-6"
            id="Apellidos"
            v-model="person.lastname"
            v-validate="'required|min:10|max:80'"
            :error="errors.first('Apellidos')"
          />
        </div>
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="DNI"
            :disabled="!!$route.params.dni"
            v-model="person.dni"
            v-validate="'required|numeric|min:8|max:8'"
            :error="errors.first('DNI')"
          />
          <div class="form-group col-md-6">
            <label for="sex2id">Sexo</label>
            <select
              class="form-control"
              id="sex2id"
              name="sexo"
              v-model="person.gender"
            >
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <m-input
            class="col-md-6"
            id="Correo"
            v-model="person.email"
            v-validate="email_validate"
            :error="errors.first('Correo')"
          />
          <m-input
            class="col-md-6"
            id="Celular"
            label="Celular"
            placeholder="Si hay varios separa con coma"
            v-model="person.phone"
            v-validate="{
              required: true,
              regex: /^[0-9,]+$/,
              min: 9,
              max: 30
            }"
            :error="errors.first('Celular')"
          />
        </div>
      </div>
      <div class="tab-pane fade" id="additional" role="tabpanel">
        <div class="form-row">
          <m-input-btn
            class="col-md-6"
            id="Distrito"
            label="Distrito"
            :readonly="true"
            v-model="person.district"
            v-validate="'required'"
            @onBtn="showM"
            :error="errors.first('Distrito')"
          />
          <m-input
            class="col-md-6"
            id="Direccion"
            v-model="person.address"
            v-validate="'required|min:4|max:150'"
            :error="errors.first('Direccion')"
          />
        </div>
        <template v-if="$route.name === 'new_f'">
          <div class="form-row">
            <m-input
              id="Profesion"
              class="col-md-6"
              label="Profesión u Ocupación"
              :error="errors.first('Profesion')"
              v-model="sub.profession"
              v-validate="'required|min:6|max:50'"
            />
            <div class="form-group col-md-6">
              <label for="levelidd">Nivel de estudio</label>
              <select
                class="form-control"
                id="levelidd"
                name="nivel"
                v-validate="'required'"
                v-model="sub.degree"
              >
                <option
                  :key="index"
                  v-for="(item, index) in subdata"
                  :value="item.name"
                >
                  {{ item.name }}
                </option>
              </select>
              <span class="small text-danger" v-show="errors.has('nivel')">
                {{ errors.first("nivel") }}
              </span>
            </div>
          </div>
          <div class="form-row">
            <m-input
              id="Telefono"
              label="Télefono de casa"
              class="col-md-6"
              :error="errors.first('Telefono')"
              v-model="sub.telephone"
              v-validate="'numeric|min:6|max:20'"
            />
            <m-input
              id="Colegio"
              class="col-md-6"
              label="Colegio de educación básica regular"
              :error="errors.first('Colegio')"
              v-model="sub.institute"
              v-validate="'min:5|max:150'"
            />
          </div>
        </template>
        <div v-else-if="$route.name === 'new_t'" class="form-row">
          <div class="form-group col-md-6">
            <label for="speid">Especialidad</label>
            <select
              class="form-control"
              id="speid"
              name="especialidad"
              v-validate="'required'"
              v-model="sub.specialty"
            >
              <option
                :key="index"
                v-for="(item, index) in subdata"
                :value="item.code"
              >
                {{ item.value }}
              </option>
            </select>
            <span class="small text-danger" v-show="errors.has('especialidad')">
              {{ errors.first("especialidad") }}
            </span>
          </div>
          <m-input
            class="col-md-6"
            type="date"
            id="Inicio"
            label="Fecha de Ingreso"
            v-model="sub.startdate"
            v-validate="`required|date_format:yyyy-MM-dd|before:${maxDate}`"
            :error="errors.first('Inicio')"
          />
        </div>
        <div class="form-row">
          <m-input
            type="date"
            class="col-md-6"
            id="Fecha"
            v-model="person.birthdate"
            v-validate="birth_validate"
            label="Fecha de nacimiento"
            :error="errors.first('Fecha')"
          />
          <m-input
            class="col-md-6"
            id="Observacion"
            v-model="person.obs"
            v-validate="'min:4|max:120'"
            :error="errors.first('Observacion')"
          />
        </div>
      </div>
    </form-tab>
    <district-modal @add="add" />
  </div>
</template>
<script>
import DistrictModal from "../../Components/Views/DistrictModal";
import FormTab from "@/Components/Card/FormTab";
import { simpledmax } from "../../Mixins/utils";
import levels from "../../Data/level.json";
import mainApi from "../../Api/main";
import request from "../../Http";
export default {
  name: "CreatePerson",
  mixins: [simpledmax],
  components: {
    FormTab,
    DistrictModal
  },
  data() {
    return {
      load: false,
      person: {
        dni: "",
        name: "",
        lastname: "",
        birthdate: "",
        gender: "",
        ubigeo: "",
        district: "",
        address: "",
        email: "",
        phone: "",
        obs: ""
      },
      sub: {
        dni: ""
      },
      subdata: [],
      arrtype: {
        new_s: "/student",
        new_f: "/family",
        new_t: "/teacher"
      }
    };
  },
  mounted() {
    const { name } = this.$route;
    if (name === "new_f") {
      this.subdata = levels;
    } else if (name === "new_t") {
      mainApi.fetchByTags("cy.m_doc_cs.d").then((r) => {
        this.subdata = r.data.configs;
        this.fetchBy();
      });
    }
    if (["new_s", "new_f"].includes(name)) {
      this.fetchBy();
    }
  },
  computed: {
    birth_validate() {
      const is = this.$route.name === "new_s" ? "required|" : "";
      return `${is}date_format:yyyy-MM-dd|before:${this.maxDate}`;
    },
    email_validate() {
      const is = this.$route.name === "new_t" ? "required|" : "";
      return `${is}email|min:6|max:50`;
    },
    route() {
      return this.arrtype[this.$route.name];
    }
  },
  methods: {
    showM() {
      $("#district").modal("show");
    },
    add(district) {
      this.person.ubigeo = district.code;
      this.person.district = district.name;
      $("#district").modal("hide");
    },
    fetchBy() {
      const { dni } = this.$route.params;
      if (dni) {
        request.get(`${this.route}/${dni}`).then(({ data }) => {
          const res = data.value;
          this.person = { ...res.person };
          delete res.person;
          this.sub = res;
        });
      }
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          const {
            params: { dni }
          } = this.$route;
          const { person, sub, route } = this;
          sub.dni = person.dni;
          person.sub = sub;
          const store = () => {
            if (dni) {
              return request.put(`${route}/${dni}`, person);
            }
            return request.post(route, person);
          };
          this.load = true;
          store()
            .then(({ data }) => {
              this.$snack.success(data.message);
              this.$router.push({
                name: `${route.substring(1)}_profile`,
                params: { dni: person.dni }
              });
            })
            .catch((error) => {
              if (error.code === 422) {
                this.$setErrors(error.data, ["dni", "DNI"]);
                this.$setErrors(error.data, ["email", "Correo"]);
              }
            })
            .finally(() => {
              this.load = false;
            });
        }
      });
    }
  }
};
</script>
