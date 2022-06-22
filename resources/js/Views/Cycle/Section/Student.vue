<template>
  <card title="Estudiantes por Sección">
    <m-button
      @pum="printCard"
      :disabled="inactives"
      icon="icon ion-md-cloud-download icon-md"
      color="btn-inverse-accent btn-icon"
      size="btn-sm"
      class="px-2"
      slot="rb"
    />
    <m-table
      v-model="buscado"
      :columns="columns"
      @ref="fetchData"
      :load="load"
      :data="registers"
    >
      <div class="d-flex align-items-center">
        <my-section @done="fetchData" />
        <m-check
          id="showinactives"
          class="ml-3"
          text="Mostrar pendientes e inactivos"
          @change="handleInactivesChange"
        />
      </div>
      <template slot="data">
        <tr v-for="item in filtered" :key="item.code">
          <td class="my-0">
            <img
              v-if="item.student.person.profile"
              :src="`/default/${item.student.person.profile.image}`"
            />
          </td>
          <td>{{ item.student_dni }}</td>
          <td>
            <router-link
              :to="{
                name: 'student_profile',
                params: { dni: item.student_dni }
              }"
              class="font-weight-medium text-primary"
            >
              {{
                `${item.student.person.name} ${item.student.person.lastname}`
              }}
            </router-link>
          </td>
          <td>
            <span class="badge" :class="[states[item.state].badge]">
              {{ states[item.state].text }}
            </span>
          </td>
          <td>{{ item.monthly | currency }}</td>
          <td>{{ item.student.person.phone }}</td>
          <td>
            <i class="text-accent" v-if="item.student.person.profile">
              {{ item.student.person.profile.last_logout | ago }}
            </i>
            <i v-else>Nunca</i>
          </td>
        </tr>
      </template>
    </m-table>
    <p slot="foot" class="text-center font-weight-medium text-primary">
      Total: {{ registers.length }} Estudiantes
    </p>
  </card>
</template>
<script>
import { mapState, mapActions } from "vuex";
import { states } from "@/Mixins";
import mySection from "@/Components/Views/mySection";
import mainapi from "@/Api/main";
export default {
  components: { mySection },
  mixins: [states],
  data() {
    return {
      columns: [
        "#",
        "DNI",
        "Nombre y Apellidos",
        "Estado",
        "Mensualidad",
        "Celular",
        "Últ. vez"
      ],
      buscado: "",
      inactives: false,
      load: false
    };
  },
  computed: {
    ...mapState("register", ["registers"]),
    filtered() {
      return this.registers.filter((item) =>
        new RegExp(this.buscado, "i").test(
          [item.student.person.name, item.student.person.lastname].join()
        )
      );
    }
  },
  methods: {
    ...mapActions("register", ["fetchBySection"]),
    fetchData() {
      this.load = true;
      this.fetchBySection(this.inactives).finally(() => {
        this.load = false;
      });
    },
    printCard() {
      const section_code = this.$store.getters["section/code"];
      mainapi.printCardS(section_code).then((r) => {
        this.$downl(r.data, `Carnet de ${section_code}`);
      });
    },
    handleInactivesChange(ischecked) {
      this.inactives = ischecked;
      this.fetchData();
    }
  }
};
</script>
