<template>
  <div class="form-group" style="max-width: 15rem; min-width: 4rem">
    <label for="mysectionid">Sección</label>
    <select
      class="form-control form-control-sm"
      id="mysectionid"
      v-model="section_code"
      @change="$emit('done')"
    >
      <option :key="item.code" v-for="item in sections" :value="item.code">
        {{ item.name }}
      </option>
    </select>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  name: "mySection",
  created() {
    this.fetchData(this.$route.params.degree_code);
  },
  watch: {
    "$route.params.degree_code"(val) {
      this.fetchData(val);
    }
  },
  computed: {
    ...mapState("section", ["sections"]),
    section_code: {
      get() {
        return this.$store.getters["section/code"];
      },
      set(val) {
        this.$store.commit("section/SET_SECTION_CODE", val);
      }
    }
  },
  methods: {
    fetchData(val) {
      this.$store.dispatch("section/fetchAll", val).then(() => {
        this.$emit("done");
      });
    }
  }
};
</script>
