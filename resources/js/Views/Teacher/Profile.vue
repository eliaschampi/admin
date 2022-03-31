<template>
  <info id="teacher-info" class="mt-3" route="new_t" :person="teacher"></info>
</template>
<script>
import { mapState } from "vuex";
import Info from "../Person/Info";
export default {
  components: { Info },
  name: "TeacherProfile",
  computed: mapState("teacher", ["teacher"])
};
</script>
