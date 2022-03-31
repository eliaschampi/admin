<template>
  <div class="mt-2">
    <form @submit.prevent="save">
      <div class="row">
        <div class="col-md-6 col-xl-4">
          <div class="form-group">
            <label for="religion">Postura religiosa</label>
            <input
              id="religion"
              type="text"
              class="form-control"
              required
              placeholder="Religión/fe cristiana que profesa"
              v-model="extra.religion"
              maxlength="50"
            />
          </div>
        </div>
        <div class="col-md-6 col-xl-4">
          <div class="form-group">
            <label for="livemode">Tipo de vínculo familiar</label>
            <select
              class="form-control"
              id="livemode"
              required
              v-model="extra.livemode"
            >
              <option value="m">Casados</option>
              <option value="c">Convivientes</option>
              <option value="d">Divorciado</option>
              <option value="v">Viudo(a)</option>
              <option value="o">Sin especificar</option>
            </select>
          </div>
        </div>
      </div>
      <m-switch
        id="consV"
        text="Su mamá y papá viven con el estudiante"
        v-model="extra.live_together"
      />
      <div class="row mt-2">
        <div class="col-md-6 col-xl-4">
          <div class="form-group">
            <label for="sweight">Peso (Kg)</label>
            <input
              id="sweight"
              type="number"
              required
              class="form-control"
              v-model="extra.weight"
              max="100"
              min="20"
            />
          </div>
        </div>
        <div class="col-md-6 col-xl-4">
          <div class="form-group">
            <label for="size">Talla (cm)</label>
            <input
              id="size"
              type="number"
              class="form-control"
              v-model="extra.size"
              required
              max="200"
              min="80"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-xl-8">
          <div class="form-group">
            <label for="additional">Descripción Adicional</label>
            <textarea
              id="additional"
              class="form-control"
              placeholder="Descripción adicional"
              v-model="extra.additional"
              maxlength="150"
            ></textarea>
          </div>
        </div>
        <div class="col-md-12 col-xl-8">
          <div class="form-group">
            <label for="reg_reason">
              ¿Porqué decidieron formar parde de la I.E?
            </label>
            <textarea
              id="reg_reason"
              class="form-control"
              v-model="extra.reg_reason"
              maxlength="200"
            ></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-xl-8 text-center">
          <alert type="alert-danger" v-show="errs.length">
            <span class="d-block" v-for="item in errs" :key="item">
              {{ item }}
            </span>
          </alert>
          <m-submit color="btn-primary">Actualizar</m-submit>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import api from "@/Api/extra";
import { fetchData } from "@/Mixins";
export default {
  mixins: [fetchData],
  data() {
    return {
      extra: this.resetValues(),
      errs: []
    };
  },
  methods: {
    save() {
      this.extra.student_dni = this.$store.getters["student/dni"];
      api
        .upsert(this.extra)
        .then((r) => {
          this.$snack.success(r.data.message);
          this.errs = [];
        })
        .catch((error) => {
          if (error.data) {
            if (typeof error.data.errors !== "undefined") {
              this.errs = Object.values(error.data.errors).map((item) => {
                return item[0];
              });
            } else {
              this.errs.push("Ocurrio un error, intente mas tarde");
            }
          }
        });
    },
    resetValues() {
      return {
        religion: "",
        livemode: "m",
        live_together: true,
        weight: null,
        size: null,
        additional: "",
        reg_reason: ""
      };
    },
    fetchData() {
      const dni = this.$store.getters["student/dni"];
      api.fetch(dni).then(({ data }) => {
        this.extra = data.value || this.resetValues();
      });
    }
  }
};
</script>
