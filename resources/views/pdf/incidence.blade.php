@extends("layout.main")
@section('content')
    <h4 class="title text-primary">
        FICHA DE INCIDENCIA
    </h4>
    <hr />
    <table>
        <tr>
            <td width="30%">
                <strong> N° de Incidencia: </strong>
            </td>
            <td width="70%">
                <p>{{ $incidence->code }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong> Tipo de incidencia: </strong>
            </td>
            <td width="70%">
                <p>{{ config("main.ins.$incidence->type") }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong> Incidencia: </strong>
            </td>
            <td width="70%">
                <p>{{ $incidence->title }}</p>
            </td>
        </tr>
    </table>

    <div class="text-primary font-bold">Desarrollo:</div>
    <hr />
    <p class="text-justify">{{ $incidence->description }}</p>
    <div class="text-primary font-bold">Conclusión y acuerdos:</div>
    <hr />
    <p class="text-justify">{{ $incidence->agreement }}</p>

    <strong class="text-primary">Estudiantes Involucrados</strong>
    <hr />
    <table class="printable">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre y Apellidos</th>
                <th>Huella/Firma</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidence->students as $item)
                <tr>
                    <td>{{ $item->dni }}</td>
                    <td>{{ $item->person->name . ' ' . $item->person->lastname }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right mt-5">
        <p>
            <em><b>Cusco: </b>
                {{ \Carbon\Carbon::parse($incidence->created_at)->formatLocalized('%d de %B del %Y') }}
            </em>
        </p>
    </div>
    <div class="text-center mt-5">
        ________________________
        <div>{{ $incidence->user->name . ' ' . $incidence->user->lastname }}</div>
        <span class="text-accent text-small">{{ $incidence->user->rol->name }}</span>
    </div>
    @if (!empty($incidence->image_attached))
        <i class="mt-5 text-small text-right"><i>* Este documento tiene adjuntos</i></i>
    @endif
@endsection
