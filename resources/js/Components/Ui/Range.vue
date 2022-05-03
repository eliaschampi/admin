<template>
  <div class="d-sm-flex flex-wrap">
    <m-input
      type="date"
      id="Desde"
      issm
      v-model="$store.state.range.from"
      v-validate="fromVali"
      label="Desde la fecha"
      :error="errors.first('Desde')"
    />
    <m-input
      type="date"
      id="Hasta"
      issm
      v-model="$store.state.range.to"
      v-validate="toVali"
      label="Hasta la fecha"
      :error="errors.first('Hasta')"
    />
    <m-button
      @pum="get"
      color="btn-primary"
      size="btn-sm"
      class="search align-self-center"
      icon="icon ion-md-search"
    />
  </div>
</template>

<script>
import { mapState } from "vuex";
import { iso } from "../../Helpers/date";
export default {
  name: "Range",
  created() {
    if (this.range.from === "") {
      const curr = new Date();
      const today = new Date(
        this.$store.getters["fullyear"],
        curr.getMonth(),
        curr.getDate()
      );
      const tom = new Date(
        this.$store.getters["fullyear"],
        curr.getMonth(),
        curr.getDate()
      );
      tom.setDate(today.getDate() + 1);
      this.$store.commit("SET_RANGE", {
        from: iso(today),
        to: iso(tom)
      });
    }
  },
  computed: {
    ...mapState(["range"]),
    fromVali() {
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: this.range.to,
        after: `${this.$store.getters["fullyear"]}-01-01`
      };
    },
    toVali() {
      const date = new Date();
      date.setDate(date.getDate() + 2);
      return {
        required: true,
        date_format: "yyyy-MM-dd",
        before: iso(date),
        after: this.range.from
      };
    }
  },
  methods: {
    get() {
      this.$validator.validateAll().then((r) => {
        if (r) this.$emit("fetch");
      });
    }
  }
};
</script>
