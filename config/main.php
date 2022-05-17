<?php

return [

    "state" => [
        "a" => "Activo",
        "i" => "Inactivo",
        "f" => "Finalizó",
        "p" => "Pendiente",
    ],

    "cycle" => [
        "PRI" => "Primaria",
        "SEC" => "Secundaria",
        "GE5" => "Quinto Carrion",
        "ADM" => "Administración",
        "OP1" => "Primera Oportunidad",
        "OP2" => "Primera Oportunidad 2",
        "REF" => "Reforzamiento",
        "OR1" => "Ordinaria 1",
        "OR2" => "Ordinaria 2",
        "IN1" => "Intensivo 1",
        "IN2" => "Intensivo 2",
    ],

    "ins" => [
        "in" => "Incidencia física",
        "ve" => "Incidencia verbal",
        "ps" => "Incidencia Psicológica",
        "sx" => "Incidencia Sexual",
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

    "inspection" => [
        "a" => [
            "label" => "Aprobado",
            "color" => "success",
        ],
        "i" => [
            "label" => "Rechazado",
            "color" => "danger",
        ],
        "p" => [
            "label" => "Pendiente",
            "color" => "warning",
        ],
        "e" => [
            "label" => "Entregado",
            "color" => "success",
        ],
        "r" => [
            "label" => "Requizado",
            "color" => "info",
        ],
        "c" => [
            "label" => "Contestado",
            "color" => "primary",
        ],
        "n" => [
            "label" => "No contestado",
            "color" => "warning",
        ],
    ],

    "ins_name" => env('INS', 'Aeduca'),

    "entry_time" => env('ENTRY_TIME', '07:75'),

    "tolerance" => env('TOLERANCE', 5),
];
