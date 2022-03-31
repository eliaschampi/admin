@extends("layout.main")
@section("content")
<h4 class="title text-center text-table-title">INFORMACIÓN DEL DOCENTE</h4>
<hr />
<table style="width: 100%;">
    <tr>
        <td style="width: 30%;">
            <img src="default/deleted.png" alt="" width="160px" />
        </td>
        <td>
            <table class="printable">
                <tr>
                    <td class="w-40 text-table-title">
                        Nombre y Apellidos:
                    </td>
                    <td>
                        <b>{{$teacher->person->name}}</b>
                        {{ $teacher->person->lastname }}
                    </td>
                </tr>
                <tr>
                    <td class="w-40 text-table-title">
                        DNI:
                    </td>
                    <td>{{$teacher->dni}}</td>
                </tr>
                <tr>
                    <td class="w-40 text-table-title">
                        Fecha de Nacimiento:
                    </td>
                    <td>{{\Carbon\Carbon::parse($teacher->person->birthdate)->formatLocalized('%d de %B del %Y')}}</td>
                </tr>
                <tr>
                    <td class="w-40 text-table-title">
                        Especialidad:
                    </td>
                    <td>{{ $spe[$teacher->specialty]}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div class="mt-1 text-table-title">INFORMACIÓN DE CONTACTO</div>
<hr>
<table class="printable">
    <tr>
        <td class="w-40 text-table-title">
            Distrito:
        </td>
        <td>
            {{$teacher->person->ubigeo}} - {{ $teacher->person->district }}
        </td>
    </tr>
    <tr>
        <td class="w-40 text-table-title">
            Dirección:
        </td>
        <td>
            {{ $teacher->person->address }}
        </td>
    </tr>
    <tr>
        <td class="w-40 text-table-title">
            Celular o Telf:
        </td>
        <td>
            {{ $teacher->person->phone }}
        </td>
    </tr>
    <tr>
        <td class="w-40 text-table-title">
            Correo:
        </td>
        <td>
            {{ $teacher->person->email }}
        </td>
    </tr>
</table>

<ul>
    <li>
        <strong class="text-accent">Fecha de inicio:</strong>
        {{ $teacher->startdate }}
    </li>
    <li>
        <strong class="text-accent">Estado:</strong>
        {{ ($teacher->state ? "Activo" : "Inactivo") }}
    </li>
</ul>

<div class="text-small mt-1 text-right">
    <b>Observaciones: </b>
    {{$teacher->person->obs}}
</div>
@endsection