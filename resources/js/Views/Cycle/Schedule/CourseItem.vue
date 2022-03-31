<template>
  <div
    class="truncate text-center traslate-sm cursor-pointer bg-light pointer"
    style="max-width: 12rem"
    @click="$emit('pum', item)"
  >
    <i class="icon ion-md-folder icon-lg text-primary"></i>
    <br />
    {{ item.course.name }}
    <div class="text-sm text-accent truncate">
      {{ `${item.teacher.person.name} ${item.teacher.person.lastname}` }}
    </div>
  </div>
</template>
<script>
export default {
  name: "CourseItem",
  props: {
    item: {
      type: Object,
      default: () => {}
    }
  }
};
</script>
