@extends("layout.main")
@section('content')
    <h4 class="title text-primary">
        INFORME DE {{ $itype['label'] }} AL {{ $person }}
    </h4>
    <hr />
    <table>
        <tr>
            <td width="30%">
                <strong> N° de informe: </strong>
            </td>
            <td width="70%">
                <p>{{ $inspection->code }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong style="text-transform: capitalize;">
                    {{ $person }}:
                </strong>
            </td>
            <td width="70%">
                <p style="text-transform: capitalize;">
                    {{ $inspection->person->name . ' ' . $inspection->person->lastname }}
                </p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong style="text-transform: capitalize;">
                    Estado:
                </strong>
            </td>
            <td width="70%">
                <p class="badge badge-{{ $state['color'] }}">
                    {{ $state['label'] }}
                </p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                {{ $itype['additional'] }}
            <td>
            <td width="70%">
                {{ $inspection->additional }}
            <td>
        </tr>
    </table>
    <div class="text-primary font-bold">Descripción:</div>
    <hr />
    <p class="text-justify">{{ $inspection->description }}</p>

    <div class="text-right mt-5">
        <b>Cusco</b>
        {{ \Carbon\Carbon::parse($inspection->created_at)->formatLocalized('%d de %B del %Y') }}
    </div>

    <table class="w-full mt-10">
        <tr>
            <td width="50%" class="text-center">
                _______________________
                <div>{{ $inspection->user->name . ' ' . $inspection->user->lastname }}</div>
                <span class="text-small text-accent">
                    {{ $inspection->user->rol->name }}
                </span>
            </td>
            <td width="50%" class="text-center">
                _______________________
                <div>
                    {{ $inspection->person->name . ' ' . $inspection->person->lastname }}
                </div>
                <span class="text-small text-accent">
                    {{ $person }}
                </span>
            </td>
        </tr>
    </table>
@endsection
