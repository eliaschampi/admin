<template>
  <div class="finder">
    <m-input-btn
      :value="inputvalue"
      aria-label="Search"
      id="SearchE"
      readonly
      :placeholder="placeholder"
      icon="icon ion-md-search"
      @onBtn="handleShowModal"
    />
  </div>
</template>
<script>
import { EventBus } from "../../Helpers/bus";
import { findlabel } from "../../Mixins";
export default {
  mixins: [findlabel],
  name: "InputFinder",
  props: {
    who: String,
    fullname: String,
    mode: {
      type: Number,
      default: 2
    },
    only_current_reg: {
      type: Boolean,
      default: true
    },
    only_current_branch: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    handleShowModal() {
      const { who, mode, only_current_reg, only_current_branch } = this;
      this.$store.dispatch("updateSfmOption", {
        who,
        mode,
        only_current_reg,
        only_current_branch,
        after: () => {
          EventBus.$emit("showFinderModal");
        }
      });
    }
  },
  computed: {
    inputvalue() {
      return this.fullname || this.$store.getters[`${this.who}/fullname`];
    }
  }
};
</script>
