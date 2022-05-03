<template>
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item sidebar-category">
        <p>Sistema</p>
        <span></span>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/">
          <i class="icon ion-ios-apps menu-icon"></i>
          <span class="menu-title">Dashboard</span>
          <div class="badge badge-pink badge-pill">Nuevo</div>
        </router-link>
      </li>
      <menu-side id="sidesis" icon="icon ion-md-desktop" name="Sistema">
        <li class="nav-item">
          <router-link :to="{ name: 'about' }" class="nav-link">
            Acerca de
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'user_profile' }" class="nav-link">
            Mi Perfil
          </router-link>
        </li>
        <li class="nav-item" v-can="'ASY'">
          <router-link :to="{ name: 'config' }" class="nav-link">
            Configuración
          </router-link>
        </li>
        <li class="nav-item" v-can="'A'">
          <router-link :to="{ name: 'branch' }" class="nav-link">
            Sedes
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'main_user' }" class="nav-link">
            Usuarios
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'company' }" class="nav-link">
            Información general
          </router-link>
        </li>
        <li class="nav-item">
          <div class="nav-link pointer" @click="log">Cerrar sesión</div>
        </li>
      </menu-side>
      <li class="nav-item sidebar-category">
        <p>Registros Generales</p>
      </li>
      <menu-side
        id="sidemain"
        v-can="'S'"
        icon="icon ion-ios-construct"
        name="Mantenimiento"
      >
        <li class="nav-item" v-can="'A'">
          <router-link :to="{ name: 'course' }" class="nav-link">
            Cursos
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'customer' }" class="nav-link">
            Clientes
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'types' }" class="nav-link">
            Tipos
          </router-link>
        </li>
      </menu-side>
      <menu-side id="sidedoc" icon="icon ion-ios-people" name="Docente">
        <li class="nav-item">
          <router-link
            :to="{
              name: 'teacher_profile',
              params: { dni: t_dni }
            }"
            class="nav-link"
          >
            Perfil del docente
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'main_teacher' }" class="nav-link">
            Listado
          </router-link>
        </li>
      </menu-side>
      <menu-side id="sidefam" icon="icon ion-md-people" name="Apoderado">
        <li class="nav-item">
          <router-link
            :to="{
              name: 'family_profile',
              params: { dni: f_dni }
            }"
            class="nav-link"
          >
            Perfil del apoderado
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'new_f' }" class="nav-link">
            Registrar Nuevo
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{
              name: 'main_family',
              params: { degree_code: dc }
            }"
            class="nav-link"
          >
            Por Grado y Seccion
          </router-link>
        </li>
      </menu-side>
      <menu-side id="sidestu" icon="icon ion-md-person" name="Estudiante">
        <li class="nav-item">
          <router-link
            :to="{
              name: 'student_profile',
              params: { dni: s_dni }
            }"
            class="nav-link"
          >
            Perfil del estudiante
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'new_s' }" class="nav-link" v-can="'S'">
            Registrar Nuevo
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{
              name: 'section_student',
              params: { degree_code: dc }
            }"
            class="nav-link"
          >
            Por Sección
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{
              name: 'payment',
              params: { dni: s_dni }
            }"
            class="nav-link"
            v-can="'ASY'"
          >
            Pagos y Mensualidades
          </router-link>
        </li>
      </menu-side>
      <li class="nav-item sidebar-category">
        <p>Administración</p>
      </li>
      <menu-side id="sideadmi" icon="icon ion-md-folder" name="Académico">
        <li class="nav-item" v-can="'NSY'">
          <router-link :to="{ name: 'main_cycle' }" class="nav-link">
            Niveles Académicos
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'sumary' }" class="nav-link">
            Reporte Anual
          </router-link>
        </li>
        <li class="nav-item" v-can="'NS'">
          <router-link
            :to="{
              name: 'schedule',
              params: { degree_code: dc }
            }"
            class="nav-link"
          >
            Horario
          </router-link>
        </li>
        <li class="nav-item" v-can="'S'">
          <router-link
            :to="{
              name: 'register',
              params: { dni: s_dni }
            }"
            class="nav-link"
          >
            Matrícula
          </router-link>
        </li>
      </menu-side>
      <menu-side id="sidecash" v-can="'SY'" icon="icon ion-md-cart" name="Caja">
        <li class="nav-item" v-can="'S'">
          <router-link :to="{ name: 'cash' }" class="nav-link">
            Caja
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'cashes' }" class="nav-link">
            Reporte mensual
          </router-link>
        </li>
        <li class="nav-item" v-can="'S'">
          <router-link :to="{ name: 'incomes' }" class="nav-link">
            Ingresos
          </router-link>
        </li>
        <li class="nav-item" v-can="'S'">
          <router-link :to="{ name: 'expense' }" class="nav-link">
            Gastos
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'canceleds' }" class="nav-link">
            Comprobantes Anulados
          </router-link>
        </li>
      </menu-side>
      <template v-if="$store.getters.can('APNY')">
        <li class="nav-item sidebar-category">
          <p>Coordinación</p>
        </li>
        <menu-side
          id="sideatt"
          v-can="'PNY'"
          icon="icon ion-md-checkmark-circle-outline"
          name="Asistencia"
        >
          <li class="nav-item">
            <router-link
              :to="{
                name: 'automatically'
              }"
              class="nav-link"
            >
              Carnet Aeduca
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              :to="{
                name: 'main_attendance',
                params: { degree_code: dc }
              }"
              class="nav-link"
            >
              Por sección
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              :to="{
                name: 'teacher_attendance'
              }"
              class="nav-link"
            >
              Docentes
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              :to="{
                name: 'attendance_student',
                params: { dni: s_dni }
              }"
              class="nav-link"
            >
              Por Estudiante
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              :to="{
                name: 't_attendance',
                params: { dni: t_dni }
              }"
              class="nav-link"
            >
              Por Docente
            </router-link>
          </li>
          <li class="nav-item" v-can="'PN'">
            <router-link
              :to="{
                name: 'attendance_manual',
                params: { degree_code: dc }
              }"
              class="nav-link"
            >
              Registrar Manualmente
            </router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'absence' }" class="nav-link">
              Tardanzas e Inasistencias
            </router-link>
          </li>
        </menu-side>
        <menu-side id="sideinc" icon="icon ion-md-bicycle" name="Incidencias">
          <li class="nav-item" v-can="'PN'">
            <router-link :to="{ name: 'new_incidence' }" class="nav-link">
              Registrar nuevo
            </router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'main_incidence' }" class="nav-link">
              Listado
            </router-link>
          </li>
        </menu-side>
        <menu-side
          id="sideaten"
          icon="icon ion-ios-chatbubbles"
          name="Atenciones"
          v-can="'PN'"
        >
          <li class="nav-item">
            <router-link :to="{ name: 'new_attention' }" class="nav-link">
              Registrar nuevo
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              :to="{
                name: 'main_attention'
              }"
              class="nav-link"
            >
              Listado
            </router-link>
          </li>
        </menu-side>
      </template>
    </ul>
  </nav>
</template>
<script>
import { logout } from "../../Mixins";
import MenuSide from "./MenuSide";
export default {
  name: "AppSidebar",
  mixins: [logout],
  components: { MenuSide },
  computed: {
    dc() {
      return this.$store.state.degree.degree.code;
    },
    t_dni() {
      return this.$store.getters["teacher/dni"];
    },
    s_dni() {
      return this.$store.getters["student/dni"];
    },
    f_dni() {
      return this.$store.getters["family/dni"];
    }
  }
};
</script>
