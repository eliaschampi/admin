<template>
  <modal
    id="updateInspection"
    :title="`Modificar ${mitype}`"
    btn-name="Modificar"
  >
    <alert> I love you so much </alert>
  </modal>
</template>
<script>
import dec_types from "@/Data/decTypes.json";
export default {
  name: "update-inspection",
  data() {
    return {
      dec_types,
      mitype: "",
      person: "",
      fullname: "",
      inspection: {}
    };
  },
  methods: {
    showModal(mitype, person, fullname, inspection) {
      this.mitype = mitype;
      this.person = person;
      this.fullname = fullname;
      this.inspection = inspection;
      $("#updateInspection").modal("show");
    }
  }
};
</script>