<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PASSED = 'passed';
    case FORMED = 'formed';
    case DELIVERED = 'delivered';
    case CANCEL = 'cancel';

    public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
