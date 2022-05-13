@extends("layout.main")
@section('content')
    <h4 class="title text-center">
        COMPROMISO DE USO DE LA PLATAFORMA AEDUCA
    </h4>
    <hr />
    <p>
        <b>Aeduca</b> es una plataforma educativa moderna, intuitiva y única. La comunidad de estudiantes y los
        docentes pueden interactuar a través de las distintas funciónes que se detallan a continuación.
    </p>
    <ul>
        <li>
            <span class="text-table-title">
                Sistema de mensajería (Chat):
            </span>
            Sirve para la comunicación en tiempo real entre <b>todos los docentes y estudiantes</b>.
            Pueden crear grupos ilimitados, enviar audios, links, imagénes, archivos de Aeduca drive y mucho más.
            Los mensajes de cada usuario son seguros y encriptados; nadie más tiene acceso. Por tanto,
            el estudiante/docente se compromente a utilizar su chat de forma adecuada, respetuosa y responsable.
        </li>
        <li>
            <span class="text-table-title">
                Aeduca Drive:
            </span>
            Cada estudiante y docente tiene su propia nube de almacenamiento rápido, seguro y con múltiples funciones.
            No se limita a guardar tareas, ya que pueden subir cualquier tipo de archivo, video y compartir con sus
            compañeros y docentes.
        </li>
        <li>
            <span class="text-table-title">
                Sistema de questionarios y exámenes:
            </span>
            Aeduca posee una herramienta muy poderosa para elaborar exámenes y questionarios intuitivos,
            con respuestas de distinta modalidad, con imágenes, preguntas paso a paso, reportes de tiempo y mucho más,
        </li>
        <li>
            <span class="text-table-title">
                Muro de publicaciones:
            </span>
            Aeduca es más que una aula virtual, es una comunidad donde los docentes y estudiantes pueden publicar todo tipo
            de contenido educativo (<i>parecido al facebook</i>).
            Las publicaciones pueden tener sus reaciones (me gusta), comentarios y un gran valor agregado para el
            aprendizaje.
            El estudiante/docente se compromete a dar el uso frecuente y cortés a sus publicaciones y comentarios.
        </li>
        <li>
            <span class="text-table-title">
                Asistencia:
            </span>
            La plataforma Aeduca también posee herramientas para gestionar la asistencia diaria, incidencias, atenciones y
            notificaciones.
            Cada estudiante y docente deberán registrar su asistencia
            por la plataforma o con su carnet de Lunes a Viernes. También pueden subir sus justificaciones de tardanza o
            inasistencia.
        </li>
        <li>
            <span class="text-table-title">
                Sesiones:
            </span>
            Los docentes pueden crear unidades y sesiones para sus estudiantes. Cada sesión tiene un horario de inicio
            y asistencia. Los docentes deberán compartir los recursos como fichas, trabajos, videos, links, etc.
            Tambien los estudiantes deberán subir sus evidencias de aprendizaje o tareas.
        </li>
        <li>
            <span class="text-table-title">
                Calificaciones
            </span>
            Los docentes deberán gestionar las notas de los estudiantes en la plataforma, ya sea de las evidencias que
            entregan o de los
            exámentes que desarrollan.
        </li>
        <li>
            Al firmar este documento el docente/estudiante se compromete a utilizar de forma responsable su plataforma
            conforme establecido en los enunciados anteriores.
        </li>
    </ul>
    <div class="mt-1 text-table-title">DATOS IMPORTANTES DE ACCESO</div>
    <hr>
    <table class="printable">
        <tr>
            <td class="w-40 text-table-title">
                Url de acceso:
            </td>
            <td>
                <a class="text-primary" href="https://aula.carrioneduca.edu.pe">https://aula.carrioneduca.edu.pe</a>
            </td>
        </tr>
        <tr>
            <td class="w-40 text-table-title">
                Usuario de acceso
            </td>
            <td>
                {{ $profile->person_dni }}
            </td>
        </tr>
        <tr>
            <td class="w-40 text-table-title">
                Correo para recuperación de cuenta
            </td>
            <td>
                {{ $profile->person->email }}
            </td>
        </tr>
        <tr>
            <td class="w-40 text-table-title">
                Contraseña preestablecida:
            </td>
            <td>
                {{ $profile->original_password }}
            </td>
        </tr>
        <tr>
            <td class="text-muted" colspan="2">
                Esta contraseña es generada por el sistema. Dado que hay riesgo con la contraseña original, es
                responsabilidad
                del apoderado, estudiante o docente protejer su contraseña, por ello es necesario cambiar posteriormente
                aquí <a href="https://aula.carrioneduca.edu.pe/seguridad">https://aula.carrioneduca.edu.pe/seguridad</a>
            </td>
        </tr>
    </table>
    <div class="text-center mt-5">
        ________________________
        <div>{{ $profile->person->name . ' ' . $profile->person->lastname }}</div>
        <span class="text-accent text-small">Firma del usuario ó apoderado</span>
    </div>
@endsection
