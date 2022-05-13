@extends('layout.card')
@section('content')
    @foreach ($persons as $value)
        <div class="carnet">
            <img src="default/card.png" alt="bitmap">
            <div class="qr-wrapper">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate($value->dni)) !!}" alt="qr" />
            </div>
            <div class="user">
                @if (!empty($value->profile) && !empty($value->profile->image))
                    <img src="default/{!! $value->profile->image !!}" alt="Foto" />
                @else
                    <img src="default/deleted.png" alt="Foto" />
                @endif
            </div>
            <p class="dni">{{ $value->dni }}</p>
            <div class="data">
                <p style="margin-bottom: 0.5mm">
                    <b>
                        {{ $value->name }}
                        <br />
                        {{ $value->lastname }}
                    </b>
                </p>
                <span>
                    @if ($title === 'teacher' && !empty($value->teacher))
                        @if ($value->teacher->specialty === 'ADM')
                            Administración
                        @else
                            Docente - {{ config('main.cycle.' . $value->teacher->specialty) }}
                        @endif
                    @else
                        {{ $title }}
                    @endif
                </span>
            </div>
        </div>
        @if ($loop->iteration % 2 === 0)
            <br />
        @endif
    @endforeach
@endsection
