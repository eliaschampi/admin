<?php

return [

    "state" => [
        "a" => "Activo",
        "i" => "Inactivo",
        "f" => "Finalizó",
        "p" => "Pendiente"
    ],

    "cycle" => [
        "PRI" => "Primaria",
        "SEC" => "Secundaria",
        "GE5" => "Quinto Carrion",
        "SE5" => "Quinto Excelencia",
        "ADM" => "Administración",
        "ACA" => "Academia",
    ],

    "ins" => [
        "in" => "Incidencia física",
        "ve" => "Incidencia verbal",
        "co" => "Incidencia por mala conducta",
        "me" => "Incidencia médica",
        "re" => "Requisa",
        "ot" => "Otro",
    ],

    "rt" => [
        "1" => "Mamá",
        "2" => "Papá",
        "3" => "Tio(a)",
        "4" => "Primo(a)",
        "5" => "Hermano(a)",
        "8" => "Abuelo(a)",
        "9" => "Sin Especificar",
    ],

    "atype" => [
        "student" => "Estudiante",
        "family" => "Apoderado",
        "teacher" => "Docente",
    ],

    "ins_name" => env('INS', 'Aeduca'),

    "entry_time" => env('ENTRY_TIME', '07:75:00'),

    "tolerance" => env('TOLERANCE', 5),
];
