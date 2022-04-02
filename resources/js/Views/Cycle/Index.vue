<template>
  <section id="ciclo" class="px-4">
    <m-button
      v-can="'A'"
      @pum="newCycle"
      color="btn-rounded btn-accent fixed-action-btn"
    >
      Aperturar
    </m-button>
    <div class="m-3">
      <alert :dismisable="false" type="alert-success">
        Toda la información se muestra solo del año seleccionado. Si quieres ver
        del año anterior
        <router-link class="alert-link" :to="{ name: 'config' }">
          click aquí
        </router-link>
      </alert>
    </div>
    <div class="mygrid g18">
      <template v-for="item in cycles">
        <cycle-item
          :key="item.code"
          :cycle="item"
          @edit="show(item)"
          :cycle-types="cycleTypes"
        />
      </template>
    </div>
    <new-cycle
      ref="myNewCycle"
      @after="fetchAll"
      :ctypes="cycleTypes"
      :is-new="isNew"
      @close="isNew = true"
    />
  </section>
</template>
<script>
import { mapActions, mapState } from "vuex";
import NewCycle from "./NewCycle";
import CycleItem from "./CycleItem";
import mainApi from "../../Api/main";
export default {
  components: {
    NewCycle,
    CycleItem
  },
  data() {
    return {
      load: false,
      isNew: true,
      cycleTypes: {}
    };
  },
  created() {
    this.fetchAll();
    this.fetchTypes();
  },
  computed: {
    ...mapState("cycle", ["cycles"])
  },
  methods: {
    ...mapActions("cycle", ["fetchAll"]),
    newCycle() {
      this.isNew = true;
      this.$refs.myNewCycle.updateMyCycle({
        from: "",
        to: "",
        attendance: [{ order: 1, entry_time: "07:45:00", tolerance: 5 }],
        monthly: "0.00"
      });
      $("#newCycle").modal("show");
    },
    async fetchTypes() {
      const { data } = await mainApi.fetchByTagsPlucked("cy_cy.m");
      this.cycleTypes = data.configs;
    },
    show(value) {
      this.isNew = false;
      this.$refs.myNewCycle.updateMyCycle(value);
      $("#newCycle").modal("show");
    }
  }
};
</script>
