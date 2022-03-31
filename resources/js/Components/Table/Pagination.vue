<template>
  <!-- eslint-disable -->
  <ul class="pagination mb-0" v-if="data.total > data.per_page">
    <li class="page-item" v-if="data.prev_page_url">
      <span
        @click="selectPage(--data.current_page)"
        aria-label="Previous"
        class="page-link pointer"
      >
        Anterior
      </span>
    </li>
    <li
      :class="{ active: item === data.current_page }"
      :key="index"
      class="page-item"
      v-for="(item, index) in getPages()"
    >
      <a @click.prevent="selectPage(item)" class="page-link" href="#">
        {{ item }}
      </a>
    </li>
    <li class="page-item" v-if="data.next_page_url">
      <span
        @click="selectPage(++data.current_page)"
        aria-label="Next"
        class="page-link pointer"
      >
        Siguiente
      </span>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default() {
        return {
          current_page: 1,
          from: 1,
          last_page: 1,
          next_page_url: null,
          per_page: 10,
          prev_page_url: null,
          to: 1,
          total: 0
        };
      }
    },
    limit: {
      type: Number,
      default: 0
    }
  },
  methods: {
    selectPage(page) {
      if (page === "...") {
        return;
      }
      this.$emit("pagination-change-page", page);
    },
    getPages() {
      if (this.limit === -1) {
        return 0;
      }
      if (this.limit === 0) {
        return this.data.last_page;
      }
      const current = this.data.current_page;
      const last = this.data.last_page;
      const delta = this.limit;
      const left = current - delta;
      const right = current + delta + 1;
      const range = [];
      const pages = [];

      let l; //eslint-disable-line
      for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= left && i < right)) {
          range.push(i);
        }
      }
      range.forEach(function (i) {
        if (l) {
          if (i - l === 2) {
            pages.push(l + 1);
          } else if (i - l !== 1) {
            pages.push("...");
          }
        }
        pages.push(i);
        l = i;
      });
      return pages;
    }
  }
};
</script>
