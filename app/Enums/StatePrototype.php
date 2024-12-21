<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StatePrototype
{
    const EMPTY = 1;
    const WAIT = 2;
    const ONGOING = 3;
    const FINISH  = 4;
    const CONFIRM  = 5;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::EMPTY => "문구작성대기",
            self::WAIT => "시안 제작대기",
            self::ONGOING => "시안 작업중",
            self::FINISH => "제작완료",
            self::CONFIRM => "확정완료",
        ];

        return $items[$value];
    }

    public static function getValues()
    {

    }
}
