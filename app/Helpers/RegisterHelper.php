<?php

namespace App\Helpers;

/**
 *  @author Elias
 */
class RegisterHelper
{

    public static function consv($section_code, $student_name)
    {
        //mutable for another institute
        $code = substr($section_code, 4, 3);

        $modular_code = "";
        $cyclename = "";
        
        if ($code === "PRI") {
            $modular_code = "1386051";
            $cyclename = "Primaria";
        } else if ($code === "SEC") {
            $cyclename = "Secundaria";
            $modular_code = "1639277";
        }

        $data = [
            "name"  => "CARRIÓN - CUSCO",
            "student" => $student_name,
            "full_cycle" => $cyclename,
            "full_degree" => self::degree($section_code),
            "modular_code" => $modular_code,
            "section" => substr($section_code, -1)
        ];

        $pdf = \PDF::loadView("pdf.consv", compact("data"));
        return $pdf->download("consv.pdf");
    }

    public static function degree(string $section_code)
    {
        $code =  intval(substr($section_code, -2, 1));
        if ($code > 3) {
            return $code . "to";
        } else if ($code === 2) {
            return $code . "do";
        }
        return $code . "ro";
    }
}
