/**
 * @author Elias
 */
import MainLayout from "../Layout/Main";
import LoginLayout from "../Layout/Auth";
// Home
import Home from "../Views/Home";
// errors
import Error404 from "../Views/Error/404";
import Error401 from "../Views/Error/401";
// authentication
import login from "../Views/Auth/Login";
import Recover from "../Views/Auth/Recover";
// user
import MainUser from "../Views/User/Index";
import NewUser from "../Views/User/Create";
import UserProfile from "../Views/User/Profile";
//person
import CreateForm from "../Views/Person/Create.vue";
// config
import Company from "../Views/System/Company";
import About from "../Views/System/About";
import Config from "../Views/System/Index";
import Branch from "../Views/System/Branch";
// main
import Course from "../Views/Course/Index";

// student
import Container from "../Views/Person/Container.vue";
//info
import StudentInfo from "../Views/Student/Main.vue";
import InfoProfile from "../Views/Student/Profile";
import InfoRegister from "../Views/Student/Register";
import Moreover from "../Views/Student/Moreover";
import History from "../Views/Student/History";
// family
import FamilyInfo from "../Views/Family/Main.vue";
import MainFamily from "../Views/Family/Index";
//extra
import ESfamily from "../Views/Student/Family";

// teacher
import Teacher from "../Views/Teacher/Index";
import TeacherInfo from "../Views/Teacher/Main.vue";
import TeacherProfile from "../Views/Teacher/Profile";
import ScheduleTeacher from "../Views/Teacher/Schedule.vue";
// academic
import Cycle from "../Views/Cycle/Index";
// student
import Index from "../Views/Cycle/Section/Student";
// degree component
import PanelDegree from "../Components/Panel/Degree";
// academic student
import Sumary from "../Views/Cycle/Sumary";
// section
import NewSection from "../Views/Cycle/Section/Create";
import Migrate from "../Views/Cycle/Section/Migrate";
// academicTeacher and scheduling
import Schedule from "../Views/Cycle/Schedule/Schedule.vue";
import OpCreate from "../Views/Cycle/Schedule/Create.vue";
// incidence
import Incidence from "../Views/Incidence/Index";
import NewIncidence from "../Views/Incidence/Create";
import IncidenceStudent from "../Views/Incidence/ByStudent";

// attention
import Attention from "../Views/Attention/Index";
import NewAttention from "../Views/Attention/Create";
import AttentionStudent from "../Views/Attention/ByStudent";

// inspection
import Inspection from "../Views/Inspection/Index";
import NewInspection from "../Views/Inspection/Create";

// attendance student
import AttendanceSection from "../Views/Attendance/StudentAttendance";
import AttendanceStudent from "../Views/Attendance/ByStudent";
import Absence from "../Views/Attendance/Absence";
import AttendanceManual from "../Views/Attendance/Register";
import Automatically from "../Views/Attendance/Automatically";

// attendance teacher
import AttendanceTeacherMain from "../Views/Attendance/TeacherAttendance";
import AttendanceTeacher from "../Views/Attendance/ByTeacher";

// invoicing
import Invoice from "../Views/Income/Invoice";
import Income from "../Views/Income/Index";
import Canceled from "../Views/Income/Canceleds";

// cash
import Cash from "../Views/Cash/Cash";
import Cashes from "../Views/Cash/Cashes";

// expense
import Expense from "../Views/Expense/Index";
import NewExpense from "../Views/Expense/Create";
import Customer from "../Views/Customer/Index";
import Types from "../Views/Types/Index";
import Payment from "../Views/Student/Payment";

/**
 * iratyc  = it remains although the year changes
 *           esto se mantiene a pesar que el año cambie
 */
const routes = [
  {
    path: "",
    component: MainLayout,
    children: [
      {
        path: "/",
        name: "home",
        component: Home,
        meta: {
          title: "Inicio",
          iratyc: true
        }
      },
      {
        path: "/configuracion",
        name: "config",
        component: Config,
        meta: {
          roles: "AS",
          title: "Configuración",
          iratyc: true
        }
      },
      {
        path: "/sedes",
        name: "branch",
        component: Branch,
        meta: {
          roles: "A",
          title: "Sedes"
        }
      },
      {
        path: "/empresa",
        name: "company",
        component: Company,
        meta: {
          title: "Información del Colegio"
        }
      },
      {
        path: "/usuarios",
        name: "main_user",
        component: MainUser,
        meta: {
          title: "Usuarios"
        }
      },
      {
        path: "/nuevo_usuario",
        name: "new_user",
        component: NewUser,
        meta: {
          roles: "A",
          title: "Sistema: Registrar Usuario"
        }
      },
      {
        path: "/modificar_usuario/:code",
        name: "update_user",
        component: NewUser,
        meta: {
          title: "Modificar Usuario"
        }
      },
      {
        path: "/usuario",
        name: "user_profile",
        component: UserProfile,
        meta: {
          title: "Mi perfil",
          iratyc: true
        }
      },
      {
        path: "/cursos",
        name: "course",
        component: Course,
        meta: {
          roles: "A",
          title: "Registro de Cursos"
        }
      },
      {
        path: "/tipos",
        name: "types",
        component: Types,
        meta: {
          roles: "AS",
          title: "Tipos de Ingresos y Gastos"
        }
      },
      {
        path: "/teacher-form/:dni?",
        name: "new_t",
        component: CreateForm,
        meta: {
          roles: "AS",
          title: "Docente"
        }
      },
      {
        path: "/student-form/:dni?",
        name: "new_s",
        component: CreateForm,
        meta: {
          roles: "AS",
          title: "Estudiante"
        }
      },
      {
        path: "/family-form/:dni?",
        name: "new_f",
        component: CreateForm,
        meta: {
          title: "Apoderado"
        }
      },
      /**
       * Estudiante
       */
      {
        path: "/resumen/:level?",
        name: "sumary",
        component: Sumary,
        meta: {
          title: "Reporte Anual",
          iratyc: true
        }
      },
      /**
       * 01 ESTUDIANTE
       */
      {
        path: "/entity",
        component: Container,
        children: [
          {
            path: "convivencia",
            component: History,
            children: [
              {
                path: ":dni?/asistencia",
                component: AttendanceStudent,
                name: "attendance_student",
                meta: {
                  title: "Asistencia del estudiante",
                  iratyc: true
                }
              },
              {
                path: ":dni?/incidencia",
                component: IncidenceStudent,
                name: "incidence_student",
                meta: {
                  title: "Incidencias del estudiante",
                  iratyc: true
                }
              },
              {
                path: ":dni?/atenciones",
                component: AttentionStudent,
                name: "attention_student",
                meta: {
                  title: "Atenciones del estudiante",
                  iratyc: true
                }
              }
            ]
          },
          // main info
          {
            path: "estudiante",
            component: StudentInfo,
            children: [
              {
                path: ":dni?/perfil",
                name: "student_profile",
                component: InfoProfile,
                meta: {
                  iratyc: true,
                  title: "Perfil del Estudiante"
                }
              },
              {
                path: ":dni?/matricula",
                name: "register",
                component: InfoRegister,
                meta: {
                  roles: "AS",
                  title: "Matrícula del Estudiante"
                }
              },
              {
                path: ":dni/adicional",
                name: "more_over",
                component: Moreover,
                meta: {
                  roles: "AS",
                  title: "Información adicional"
                }
              },
              {
                path: ":dni?/family-info",
                name: "es_family",
                component: ESfamily,
                meta: {
                  title: "Apoderados del estudiante"
                }
              }
            ]
          },
          // pagos
          {
            path: ":dni?/pagos_y_mensualidades",
            name: "payment",
            component: Payment,
            meta: {
              roles: "AS",
              title: "Pagos y Mensualidades",
              iratyc: true
            }
          },
          // family
          {
            path: "apoderado/:dni?",
            component: FamilyInfo,
            name: "family_profile",
            meta: {
              title: "Apoderado",
              ptype: "family"
            }
          },

          {
            path: "docente",
            component: TeacherInfo,
            children: [
              {
                path: ":dni?/perfil",
                name: "teacher_profile",
                component: TeacherProfile,
                meta: {
                  title: "Docente",
                  ptype: "teacher"
                }
              },
              {
                path: ":dni?/asistencia",
                name: "t_attendance",
                component: AttendanceTeacher,
                meta: {
                  roles: "AN",
                  ptype: "teacher",
                  title: "Docente",
                  iratyc: true
                }
              },
              {
                path: ":dni?/horario",
                name: "t_schedule",
                component: ScheduleTeacher,
                meta: {
                  roles: "ASN",
                  ptype: "teacher",
                  title: "Docente"
                }
              }
            ]
          }
        ]
      },
      {
        path: "/docentes",
        name: "main_teacher",
        component: Teacher,
        meta: {
          title: "Módulo de Docentes"
        }
      },
      {
        path: "/asistencia_docente",
        name: "teacher_attendance",
        component: AttendanceTeacherMain,
        meta: {
          roles: "AN",
          title: "Asistencia del docente",
          iratyc: true
        }
      },

      /**
       * ciclo
       */
      {
        path: "/niveles_academicos",
        name: "main_cycle",
        component: Cycle,
        meta: {
          roles: "ANS",
          title: "Niveles académicos",
          iratyc: true
        }
      },
      /**
       * grado
       */
      {
        path: "/grado",
        component: PanelDegree,
        children: [
          {
            path: ":degree_code?/estudiantes",
            name: "section_student",
            component: Index,
            meta: {
              title: "Estudiantes",
              iratyc: true
            }
          },
          {
            path: ":degree_code?/horario",
            name: "schedule",
            component: Schedule,
            meta: {
              title: "Horario",
              roles: "ANS",
              iratyc: true
            }
          },
          {
            path: ":degree_code?/newschedule",
            name: "new_schedule",
            component: OpCreate,
            meta: {
              roles: "AS",
              title: "Nuevo Horario"
            }
          },
          {
            path: ":degree_code?/registrar_seccion",
            name: "new_section",
            component: NewSection,
            meta: {
              roles: "A",
              title: "Registrar una Sección"
            }
          },
          {
            path: ":degree_code?/migracion",
            name: "migrate_student",
            component: Migrate,
            meta: {
              roles: "A",
              title: "Migración al nuevo Grado"
            }
          },

          // p
          {
            path: ":degree_code?/asistencia_por_seccion",
            name: "main_attendance",
            component: AttendanceSection,
            meta: {
              roles: "APN",
              title: "Asistencia por sección",
              iratyc: true
            }
          },
          {
            path: ":degree_code?/registrar_asistencia",
            name: "attendance_manual",
            component: AttendanceManual,
            meta: {
              roles: "APN",
              title: "Asistencia del Estudiante"
            }
          },
          // apoderados
          {
            path: ":degree_code?/apoderados",
            name: "main_family",
            component: MainFamily,
            meta: {
              title: "Apoderados",
              iratyc: true
            }
          }
        ]
      },

      /**
       * incidencias
       */
      {
        path: "incidencias",
        name: "main_incidence",
        component: Incidence,
        meta: {
          roles: "APN",
          title: "Módulo de Incidencias",
          iratyc: true
        }
      },
      {
        path: "incidencias/create/:code?",
        name: "new_incidence",
        component: NewIncidence,
        meta: {
          roles: "APN",
          title: "Registro de Incidencia"
        }
      },
      {
        path: "/atenciones",
        name: "main_attention",
        component: Attention,
        meta: {
          roles: "APN",
          title: "Atenciones",
          iratyc: true
        }
      },
      {
        path: "atenciones/create/:code?",
        name: "new_attention",
        component: NewAttention,
        meta: {
          roles: "APN",
          title: "Registro de Atención"
        }
      },
      {
        path: "cedp",
        name: "cedp",
        component: Inspection,
        meta: {
          title: "Registros normativos",
          roles: "APN"
        }
      },
      {
        path: "cedp/create",
        name: "new_inspection",
        component: NewInspection,
        meta: {
          title: "Registros normativos",
          roles: "APN"
        }
      },
      {
        path: "inasistencias/:dni?",
        name: "absence",
        component: Absence,
        meta: {
          roles: "APN",
          title: "Tardanzas e Inasistencias"
        }
      },
      {
        path: "asistencia_diaria",
        name: "automatically",
        component: Automatically,
        meta: {
          roles: "AN",
          title: "Registrar Asistencia"
        }
      },
      /**
       * Facturación
       */
      {
        path: "clientes",
        name: "customer",
        component: Customer,
        meta: {
          roles: "AS",
          title: "Clientes"
        }
      },
      {
        path: "cash/history",
        name: "cashes",
        component: Cashes,
        meta: {
          roles: "AS",
          title: "Historial de Caja",
          iratyc: true
        }
      },
      {
        path: "ingreso/create",
        name: "invoice",
        component: Invoice,
        meta: {
          roles: "AS",
          title: "Ventas e Ingresos"
        }
      },
      {
        path: "ingresos",
        name: "incomes",
        component: Income,
        meta: {
          roles: "AS",
          title: "Ingresos de Hoy",
          iratyc: true
        }
      },
      {
        path: "anulados",
        name: "canceleds",
        component: Canceled,
        meta: {
          roles: "AS",
          title: "Comprobantes Anulados",
          iratyc: true
        }
      },
      {
        path: "expense/create",
        name: "new_expense",
        component: NewExpense,
        meta: {
          roles: "AS",
          title: "Registrar un Gasto"
        }
      },
      {
        path: "/expense/update/:code",
        name: "update_expense",
        component: NewExpense,
        meta: {
          roles: "AS",
          title: "Modificar Apoderado"
        }
      },
      {
        path: "egresos",
        name: "expense",
        component: Expense,
        meta: {
          roles: "AS",
          title: "Gastos",
          iratyc: true
        }
      },
      {
        path: "caja",
        name: "cash",
        component: Cash,
        meta: {
          roles: "AS",
          title: "Caja"
        }
      },
      {
        path: "sistema",
        name: "about",
        component: About,
        meta: {
          title: "Acerca de"
        }
      },
      {
        path: "/",
        name: "not_auth",
        component: Error401,
        meta: {
          iratyc: true
        }
      }
    ]
  },
  {
    path: "",
    component: LoginLayout,
    children: [
      {
        path: "/login",
        name: "login",
        component: login
      },
      {
        path: "/recover",
        name: "recover",
        component: Recover
      },
      {
        path: "*",
        name: "not_found",
        component: Error404
      }
    ]
  }
];

export default routes;
