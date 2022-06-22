<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: 5.4cm 8.6cm landscape;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 21px;
        }
    </style>
    <link rel="stylesheet" href="css/card.css">
</head>

<body>
    <div class="carnet">
        <img src="default/card.png" alt="bitmap">
        <div class="qr-wrapper">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate($person->dni)) !!}" alt="qr" />
        </div>
        <div class="user">
            @if (!empty($person->profile) && !empty($person->profile->image))
                <img src="default/{!! $person->profile->image !!}" alt="Foto" />
            @else
                <img src="default/deleted.png" alt="Foto" />
            @endif
        </div>
        <p class="dni">{{ $person->dni }}</p>
        <div class="data">
            <p style="margin-bottom: 0.5mm">
                <b>
                    {{ $person->name }}
                    <br />
                    {{ $person->lastname }}
                </b>
            </p>
            <span>
                {{ $title }}
            </span>
        </div>
    </div>
</body>

</html>
