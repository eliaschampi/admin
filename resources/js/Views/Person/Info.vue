<template>
  <div>
    <m-plain label="DNI:" :value="person.dni" />
    <m-plain
      label="Sexo:"
      :value="person.gender === 'M' ? 'Hombre' : 'Mujer'"
    />
    <m-plain label="Fecha de Nacimiento:" :value="person.birthdate | date" />
    <m-plain label="Correo:" :value="person.email" />
    <m-plain label="Celular:" :value="person.phone" />
    <m-plain label="Dirección:" :value="address" />
    <m-plain
      label="Fecha de Registro:"
      :value="person.created_at | largeDate"
    />
    <m-plain label="Modificado el:" :value="person.updated_at | largeDate" />
    <m-plain label="Observaciones:" :value="person.obs" />
    <slot></slot>
    <div class="button-group">
      <m-router
        v-can="'AS'"
        color="btn-inverse-primary"
        class="btn-icon"
        :to="{ name: route, params: { dni: person.dni } }"
        icon="icon ion-md-create icon-md"
      >
      </m-router>
      <m-button
        size="btn-sm"
        color="btn-inverse-danger btn-icon"
        icon="icon ion-md-trash"
        @pum="handleDeleteClick"
      >
      </m-button>
    </div>
  </div>
</template>
<script>
import api from "../../Api/person";
export default {
  props: ["route", "person"],
  computed: {
    address() {
      const { ubigeo, district, address } = this.person;
      return `${ubigeo}: ${district} ${address}`;
    }
  },
  methods: {
    handleDeleteClick() {
      this.$snack.show({
        text: "¿Estas seguro de eliminar toda la información?",
        button: "CONFIRMAR",
        action: () => {
          api.del(this.person.dni).then((r) => {
            this.$snack.show(r.data);
            this.$router.push({ name: "home" });
          });
        }
      });
    }
  }
};
</script>
