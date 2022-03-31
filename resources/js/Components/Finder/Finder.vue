<template>
  <div class="row">
    <form v-on:submit.prevent="search" class="col-md-12 input-group">
      <input
        type="text"
        :placeholder="placeholder"
        class="form-control"
        v-model="name"
      />
      <div class="input-group-append">
        <m-submit
          :load="loading"
          color="btn-outline-primary"
          icon="ion ion-ios-search"
        />
        <div class="dropdwon" v-show="!only_current_reg && who === 'student'">
          <m-button
            color="btn-inverse-accent"
            icon="ion ion-md-funnel"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-expanded="false"
          />
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <template v-if="!only_current_branch">
              <template v-for="item in branches">
                <li class="dropdown-item" :key="item.code" @click.stop>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="branch_r"
                      :value="item.code"
                      v-model="branch_code"
                    />
                    <label
                      class="form-check-label"
                      @click="branch_code = item.code"
                    >
                      {{ item.name }}
                    </label>
                  </div>
                </li>
              </template>
            </template>
            <li class="dropdown-item form-check" @click.stop>
              <label class="pointer user-select-none">
                <input
                  type="checkbox"
                  aria-label="onlystudent"
                  v-model="only_reg"
                />
                Solo estudiantes {{ $store.getters["fullyear"] }}
              </label>
            </li>
          </ul>
        </div>
      </div>
    </form>
    <div class="col-md-12">
      <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="item in values" :key="item.dni">
          <div class="form-check">
            <input
              :id="item.dni"
              name="grouped"
              type="radio"
              class="form-check-input"
              :value="item.person"
              v-model="selected"
              @change="handleItemChange(item.branch_code)"
            />
            <label class="form-check-label" :for="item.dni">
              {{ item.person.name + " " + item.person.lastname }}
            </label>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import { EventBus } from "../../Helpers/bus";
import request from "../../Http/index";
import { findlabel } from "../../Mixins";
export default {
  name: "Finder",
  mixins: [findlabel],
  props: {
    prevname: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      name: this.prevname,
      values: [],
      selected: null,
      loading: false,
      only_reg: true,
      branch_code: null
    };
  },
  watch: {
    who() {
      this.values = [];
    }
  },
  created() {
    EventBus.$on("sfm_has_closed", this.resetMyOptionValues);
  },
  mounted() {
    const dni = this.$store.getters[`${this.who}/dni`];
    this.branch_code = this.$store.getters["user/branch_code"];
    if (!this.name && dni) {
      this.name = this.$store.getters[`${this.who}/fullname`];
    }
  },
  computed: {
    ...mapState({
      who: (state) => state.sfm_ops.who,
      only_current_reg: (state) => state.sfm_ops.only_current_reg,
      only_current_branch: (state) => state.sfm_ops.only_current_branch
    }),
    branches() {
      return this.$store.getters["branches"];
    }
  },
  methods: {
    resetMyOptionValues() {
      this.only_reg = true;
      this.branch_code = this.$store.getters["user/branch_code"];
    },
    search() {
      if (!this.loading) {
        let { name } = this;
        name = name.trim();
        if (!name || name === "") return;
        this.loading = true;
        let route = `/${this.who}/search/${name}`;
        if (this.who === "student") {
          route = `/student/search/${this.branch_code}/${this.only_reg}/${name}`;
        }
        request
          .get(route)
          .then(({ data }) => {
            this.values = data.values;
          })
          .finally(() => {
            setTimeout(() => {
              this.loading = false;
            }, 700);
          });
      }
    },
    handleItemChange(branch_code) {
      this.$emit("input", { ...this.selected, branch_code });
    }
  },
  beforeDestroy() {
    EventBus.$off("sfm_has_closed", this.resetMyOptionValues);
  }
};
</script>
