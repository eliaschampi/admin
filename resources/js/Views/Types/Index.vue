<template>
  <section id="cats">
    <card title="Tipos de Ingresos y gastos">
      <m-button
        @pum="show"
        color="btn-inverse-accent btn-icon"
        size="btn-sm"
        class="px-2"
        slot="rb"
        icon="icon ion-md-add icon-md"
      >
      </m-button>
      <div class="row">
        <m-table
          :columns="columns"
          :data="cats"
          :fetch="fetchAll"
          class="col-md-12"
        >
          <div class="form-group" style="max-width: 15rem">
            <label for="farf">Tipo</label>
            <select
              class="form-control form-control-sm"
              id="farf"
              @change="fetchAll"
              v-model="mode"
            >
              <option :key="item.code" v-for="item in modes" :value="item.name">
                {{ item.name }}
              </option>
            </select>
          </div>
          <template slot="data">
            <tr :key="item.code" v-for="item in cats">
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>
                <m-action @action="edit(item)" />
              </td>
            </tr>
          </template>
        </m-table>
      </div>
      <p slot="foot" class="text-center font-weight-medium text-primary">
        Total de registros: {{ cats.length }}
      </p>
    </card>
    <modal :btnName="btnName" :title="title" @sub="save" id="mymodal">
      <div class="form-row">
        <m-input
          class="col-md-12"
          id="Tipo"
          v-model="cat.name"
          v-validate="'required|min:2|max:50'"
          label="Descripción"
          :error="errors.first('Tipo')"
        />
      </div>
    </modal>
  </section>
</template>
<script>
import catApi from "../../Api/cat";
export default {
  data() {
    return {
      cat: {},
      cats: [],
      title: "Registrar nuevo Ingreso",
      btnName: "Guardar",
      option: "create",
      columns: ["Código", "Descripción", "Acciones"],
      mode: "Ingreso",
      modes: [
        {
          name: "Ingreso"
        },
        {
          name: "Egreso"
        }
      ]
    };
  },
  methods: {
    fetchAll() {
      return catApi.fetch(this.mode).then((r) => {
        this.cats = r.data.values;
      });
    },
    save() {
      this.$validator.validateAll().then((r) => {
        if (r) {
          this.store(this.cat).then((res) => {
            $("#mymodal").modal("hide");
            this.$snack.success(res);
            this.cat = {};
          });
        }
      });
    },
    async store(cat) {
      if (this.option === "create") {
        cat.mode = this.mode;
        const { data: d1 } = await catApi.store(cat);
        this.cats.push(d1.cat);
        return d1.message;
      }
      const { data } = await catApi.update(cat);
      const item = this.cats.find((i) => i.code === cat.code);
      this.cats.splice(this.cats.indexOf(item), 1, cat);
      return data;
    },
    edit(cat) {
      this.option = "edit";
      this.cat = Object.assign({}, cat);
      this.btnName = "Guardar Cambios";
      this.title = `Modificar ${this.mode}`;
      $("#mymodal").modal("show");
    },
    show() {
      this.option = "create";
      this.cat = {};
      this.btnName = "Guardar";
      this.title = `Registrar Nuevo ${this.mode}`;
      $("#mymodal").modal("show");
    }
  }
};
</script>
