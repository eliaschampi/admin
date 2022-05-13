@extends("layout.main")
@section('content')
    <h4 class="title text-primary">
        ATENCIÓN AL
        {{ strtoupper(
            config("main.atype.$attention->person_type") . ' ' . $attention->person->name . ' ' . $attention->person->lastname,
        ) }}
    </h4>
    <hr />
    <table>
        <tr>
            <td width="30%">
                <strong> N° de Atención: </strong>
            </td>
            <td width="70%">
                <p>{{ $attention->code }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong> Modalidad: </strong>
            </td>
            <td width="70%">
                <p>{{ $attention->type === 'p' ? 'Precencial' : 'Virtual' }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong> Motivo: </strong>
            </td>
            <td width="70%">
                <p>{{ $attention->title }}</p>
            </td>
        </tr>
    </table>

    <div class="text-primary font-bold">El entrevistador compartió:</div>
    <hr />
    <p class="text-justify">{{ $attention->introduction }}</p>

    <div class="text-primary font-bold">El {{ config("main.atype.$attention->person_type") }} compartió:</div>
    <hr />
    <p class="text-justify">{{ $attention->description }}</p>

    <div class="text-primary font-bold">Acuerdos y conclusiones:</div>
    <hr />
    <p class="text-justify">{{ $attention->conclusion }}</p>

    <div class="text-right mt-5">
        <b>Cusco</b>
        {{ \Carbon\Carbon::parse($attention->created_at)->formatLocalized('%d de %B del %Y') }}
    </div>

    <table class="table printable table-bordered" style="margin-top: 1cm">
        <thead>
            <tr>
                <th style="width: 5cm">
                    {{ $attention->user->name . ' ' . $attention->user->lastname }}
                    <br />
                    <small>Psicólogo</small>
                </th>
                <th style="width: 5cm">
                    {{ $attention->person->name . ' ' . $attention->person->lastname }}
                    <br />
                    <small>{{ config("main.atype.$attention->person_type") }}</small>
                </th>
                <th></th>
                <th></th>
                </td>
        </thead>
        <tbody>
            <tr>
                <td style="width: 5cm">&nbsp;</td>
                <td style="width: 5cm">&nbsp;</td>
                <td style="width: 3cm">&nbsp;</td>
                <td style="width: 3cm">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    @if (!empty($attention->file_attached))
        <i class="text-small text-right"><i>* Este documento tiene adjuntos</i></i>
    @endif
@endsection
