<?php

namespace App;


use DateTime;

class DateHelpers
{

    static function brToSql(String $date) : DateTime {
        return  DateTime::createFromFormat('d/m/Y', $date);
    }

}
