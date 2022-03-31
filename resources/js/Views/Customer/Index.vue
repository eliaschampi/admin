<template>
  <section id="customer">
    <card title="Registro de Clientes">
      <m-button
        @pum="show"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        class="px-2"
        icon="icon ion-md-add icon-md"
        slot="rb"
      />
      <div class="row">
        <m-table
          :columns="columns"
          :data="customers"
          :ref="fetchAll"
          :head="false"
          class="col-md-12"
        >
          <template slot="data">
            <tr :key="item.code" v-for="item in customers">
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.contact_number }}</td>
              <td>
                <m-action @action="edit(item)" />
                <m-action
                  @action="delC(item)"
                  icon="trash"
                  color="danger"
                  tool="Eliminar"
                />
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Clientes: {{ customers.length }}
      </p>
    </card>
    <modal id="customer_modal" :btnName="btnName" :title="title" @sub="save">
      <div class="form-row">
        <m-input
          class="col-md-6"
          id="Nombre"
          v-model="customer.name"
          label="Nombre o Razon Social"
          v-validate="'required'"
          :error="errors.first('Nombre')"
        />
        <m-input
          class="col-md-6"
          id="Telf"
          v-model="customer.contact_number"
          label="Telf de Contacto"
          v-validate="'max:30'"
          :error="errors.first('Telf')"
        />
      </div>
    </modal>
  </section>
</template>
<script>
import api from "../../Api/customer";
export default {
  data() {
    return {
      customer: {},
      customers: [],
      title: "Registrar un nuevo Cliente",
      btnName: "Guardar",
      option: "create",
      columns: ["Código", "Nombre o Razon Social", "N° telf.", "Acciones"]
    };
  },
  created() {
    this.fetchAll();
  },
  methods: {
    fetchAll() {
      api.fetchAll().then((r) => {
        this.customers = r.data.values;
      });
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.store(this.customer).then((res) => {
            $("#customer_modal").modal("hide");
            this.$snack.success(res);
            this.customer = {};
          });
        }
      });
    },
    async store(customer) {
      if (this.option === "create") {
        const { data } = await api.store(customer);
        this.customers.push(data.customer);
        return data.message;
      }
      const { data } = await api.update(customer);
      const item = this.customers.find((i) => i.code === customer.code);
      this.customers.splice(this.customers.indexOf(item), 1, customer);
      return data;
    },
    delC(customer) {
      this.$snack.show({
        text: this.$confirm("delete", "el cliente seleccionado"),
        button: "CONFIRMAR",
        action: () => {
          api.del(customer.code).then((r) => {
            this.customers.splice(this.customers.indexOf(customer), 1);
            this.$snack.show(r.data);
          });
        }
      });
    },
    edit(customer) {
      this.option = "edit";
      this.customer = Object.assign({}, customer);
      this.btnName = "Guardar Cambios";
      this.title = "Modificar un Cliente";
      $("#customer_modal").modal("show");
    },
    show() {
      this.option = "create";
      this.customer = {};
      this.btnName = "Guardar";
      this.title = "Registrar un Nuevo Cliente";
      $("#customer_modal").modal("show");
    }
  }
};
</script>
