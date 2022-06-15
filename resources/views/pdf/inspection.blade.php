@extends('layout.main')
@section('content')
    <h4 class="title text-primary" style="text-transform: uppercase;">
        INFORME DE {{ $itype['label'] }} AL {{ $person }}
    </h4>
    <hr />
    <table>
        <tr>
            <td width="30%" class="font-bold">
                N° de informe:
            </td>
            <td width="70%">
                <p>{{ $inspection->code }}</p>
            </td>
        </tr>
        <tr>
            <td width="30%" class="font-bold">
                {{ $person }}:
            </td>
            <td width="70%">
                {{ $inspection->person->name . ' ' . $inspection->person->lastname }}
            </td>
        </tr>
        <br />
        <tr>
            <td width="30%" class="font-bold">
                Estado:
            </td>
            <td width="70%">
                {{ $state['label'] }}
            </td>
        </tr>
        <br />
        <tr>
            <td width="30%" class="font-bold">
                {{ $itype['additional'] }}:
            </td>
            <td width="70%">
                {{ ' ' . $inspection->additional }}
                @if ($inspection->inspection_type === 'p')
                    <i>(Permiso por {{ $inspection->extra }} dias)</i>
                @endif
            </td>
        </tr>
    </table>

    <div class="text-primary font-bold mt-5">DESCRIPCIÓN:</div>

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
                @if (!empty($inspection->user))
                    <div>{{ $inspection->user->name . ' ' . $inspection->user->lastname }}</div>
                    <span class="text-small text-accent">
                        {{ $inspection->user->rol->name }}
                    </span>
                @else
                    <div>Coordinador de la institución</div>
                @endif
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
