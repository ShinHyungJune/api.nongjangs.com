<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arr extends Model
{
    use HasFactory;

    public static function getArrayToString($items)
    {
        $result = "";

        $index = 1;

        if($items){
            foreach($items as $item){
                if($index == 1)
                    $result .= $item;
                else
                    $result .= ", ".$item;

                $index++;
            }
        }

        return $result;
    }

    public static function getStringToArray($string, $token = ",")
    {
        if(!$string)
            return [];

        if($token == '\n')
            $result = array_map('trim', preg_split("/\r\n|\n|\r/", $string));
        else
            $result = array_map('trim', explode($token, $string));

        return $result;
    }
}
