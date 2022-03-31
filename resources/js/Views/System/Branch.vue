<template>
  <section id="branch">
    <m-button @pum="addNew" color="btn-rounded btn-accent fixed-action-btn">
      <i class="icon ion-ios-add icon-md"></i>
    </m-button>
    <div class="mygrid g18">
      <div :key="item.code" v-for="item in branches">
        <card
          icon="ion-ios-git-branch font-weight-bold"
          :f="false"
          :title="item.name"
        >
          <m-button
            color="btn-inverse-primary btn-icon"
            icon="icon ion-md-options"
            slot="rb"
            @pum="edit(item)"
          />
          <m-plain
            wraped="true"
            label="Correo de Contacto:"
            :value="item.email"
          />
          <m-plain wraped="true" label="Dirección:" :value="item.address" />
          <m-plain wraped="true" label="Teléfono:" :value="item.telephone" />
          <m-plain
            wraped="true"
            label="Código modular:"
            :value="item.modular"
          />
        </card>
      </div>
    </div>
    <modal @sub="saveBranch" btnName="Guardar" id="branchModal" title="Sede">
      <m-input
        id="sede"
        v-validate="'required|min:2|max:100'"
        v-model="branch.name"
        label="Nombre de la Sede"
        :error="errors.first('sede')"
      />
      <m-input
        id="Correo"
        type="email"
        v-validate="'required|email|min:5|max:100'"
        v-model="branch.email"
        label="Correo de Contacto"
        :error="errors.first('Correo')"
      />
      <m-input
        id="direccion"
        v-validate="'required|min:4|max:100'"
        v-model="branch.address"
        label="Direccion"
        :error="errors.first('direccion')"
      />
      <m-input
        id="telefono"
        v-validate="'required|min:2|max:50'"
        v-model="branch.telephone"
        label="Telefono de Contacto"
        :error="errors.first('telefono')"
      />
      <m-input
        id="modular"
        v-validate="'required|min:2|max:50'"
        v-model="branch.modular"
        label="Codigo Modular"
        :error="errors.first('modular')"
      />
    </modal>
  </section>
</template>
<script>
import api from "../../Api/branch";

export default {
  data() {
    return {
      branches: [],
      branch: {},
      option: "create"
    };
  },
  created() {
    api.fetchAll().then((r) => {
      this.branches = r.data.values;
    });
  },
  methods: {
    addNew() {
      this.branch = {};
      this.option = "create";
      $("#branchModal").modal("show");
    },
    edit(item) {
      this.option = "edit";
      this.branch = item;
      $("#branchModal").modal("show");
    },
    saveBranch() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          if (this.option === "create") {
            api.set(this.branch).then((r) => {
              this.branches.push(this.branch);
              this.$snack.success(r.data);
              $("#branchModal").modal("hide");
            });
          } else {
            api.update(this.branch).then((r) => {
              this.$snack.success(r.data);
              this.branches.splice(
                this.branches.indexOf(this.branch),
                1,
                this.branch
              );
              $("#branchModal").modal("hide");
            });
          }
        }
      });
    }
  }
};
</script>
