import { iso } from "../Helpers/date";
import shApi from "../Api/schedule";
/**
 * @name incomeMod
 */
export const incomeMod = {
  data() {
    return {
      types: {
        "00": "Nota de Venta",
        "03": "Boleta de Venta"
      }
    };
  }
};

/**
 * @name simpledmax
 */
export const simpledmax = {
  computed: {
    maxDate() {
      const date = new Date();
      date.setDate(date.getDate() + 1);
      return iso(date);
    }
  }
};

export const hasDetailModal = {
  methods: {
    showDetailModal() {
      this.$store.dispatch("fetchCats");
      $("#addDetail").modal("show");
    }
  }
};

export const saveAsImage = {
  methods: {
    saveAsImage(ref) {
      const comp = this.$refs[ref];
      //so i watn its canvas
      const dataString = comp.$refs.canvas.toDataURL("image/png");
      const link = document.createElement("a");
      link.download = ref;
      link.href = dataString;
      link.click();
    }
  }
};

export const deleteSH = {
  methods: {
    deleteSh(item) {
      shApi.del(item.code).then((r) => {
        this.$snack.success(r.data);
        this.schedules.splice(this.schedules.indexOf(item), 1);
      });
    }
  }
};
