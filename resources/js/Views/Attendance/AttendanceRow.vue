<!-- eslint-disable -->
<template>
  <tr>
    <slot></slot>
    <template v-if="mod === item.code">
      <td>
        <input
          style="max-width: 7rem"
          class="form-control form-control-sm"
          max="19:00"
          min="07:00"
          type="time"
          v-model="item.entry_time"
          v-show="isTimeRequired"
          value="8:00"
        />
      </td>
      <td>
        <select
          style="max-width: 6rem"
          class="form-control form-control-sm"
          v-model="item.state"
        >
          <option value="permiso">Permiso</option>
          <option value="falta">Falta</option>
          <option value="presente">Presente</option>
          <option value="tarde">Tarde</option>
        </select>
      </td>
      <td>
        <m-action
          v-show="disableSaveModButton"
          @action="$emit('update', item)"
          color="success"
          icon="save"
        />
        <m-action
          @action="$emit('cancelEdit', item)"
          icon="close"
          color="danger"
          tool="Cancelar"
        />
      </td>
    </template>
    <template v-else>
      <td>{{ item.entry_time }}</td>
      <td>
        <span :class="states[item.state]">{{ item.state }}</span>
      </td>
      <td>
        <m-action
          v-show="isAbsence"
          @action="$emit('showJusModal', item)"
          icon="hand"
          color="info"
          tool="Justificación"
        />
        <m-action v-show="!item.justification" @action="$emit('edit', item)" />
      </td>
    </template>
  </tr>
</template>
<script>
export default {
  props: {
    item: {
      type: Object,
      default: () => {
        return { state: "" };
      }
    },
    states: {
      type: Object,
      default: () => {}
    },
    mod: {
      type: Number,
      default: 0
    }
  },
  computed: {
    isAbsence() {
      return (
        ["justificado", "envió justificación", "falta"].indexOf(
          this.item.state
        ) !== -1
      );
    },
    isTimeRequired() {
      return ["presente", "tarde"].includes(this.item.state);
    },
    disableSaveModButton() {
      return !(this.isTimeRequired && !this.item.entry_time);
    }
  }
};
</script>
