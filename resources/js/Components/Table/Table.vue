<template>
  <div>
    <div class="d-flex flex-wrap mb-4 align-items-center" v-if="head">
      <div class="col-md-8">
        <slot></slot>
      </div>
      <div class="ml-auto mt-4 mt-md-0" style="max-width: 15rem">
        <m-input-btn
          :value="value"
          @input="updateself"
          aria-label="Search"
          id="SearchT"
          placeholder="Buscar"
          issm
          icon="icon ion-md-refresh"
          @onBtn="ref"
        />
      </div>
    </div>
    <div class="table-responsive">
      <pulse-loader class="text-center" v-if="loading || load" />
      <table
        class="table table-hover table-striped table-sm"
        v-else-if="data.length"
      >
        <thead>
          <tr class="font-weight-bold">
            <th :key="index" v-for="(item, index) in columns">
              {{ item }}
            </th>
          </tr>
        </thead>
        <template>
          <tbody>
            <slot name="data" v-bind:rows="data"></slot>
          </tbody>
        </template>
        <tfoot>
          <tr>
            <td :colspan="columns.length">
              <slot name="foot"></slot>
            </td>
          </tr>
        </tfoot>
      </table>
      <empty v-else :title="emptytext"></empty>
    </div>
  </div>
</template>
<script>
import Empty from "../Lab/Empty.vue";
export default {
  name: "MTable",
  components: { Empty },
  props: {
    columns: {
      type: Array,
      default: () => {
        return [];
      }
    },
    data: {
      type: Array,
      default: () => {
        return [];
      }
    },
    head: {
      type: Boolean,
      default: true
    },
    value: {
      type: String,
      default: ""
    },
    load: {
      type: Boolean,
      default: false
    },
    emptytext: {
      type: String,
      default: "Aún no hay registros"
    },
    fetch: {
      type: Function,
      default: () => {
        return new Promise((resolve) => {
          resolve();
        });
      }
    }
  },
  data() {
    return {
      loading: false
    };
  },
  mounted() {
    if (this.data.length === 0) {
      this.get();
    }
  },
  methods: {
    updateself(value) {
      this.$emit("input", value);
    },
    get() {
      this.loading = true;
      this.fetch().finally(() => {
        this.loading = false;
      });
    },
    ref() {
      this.$emit("ref");
      this.get();
    }
  }
};
</script>
