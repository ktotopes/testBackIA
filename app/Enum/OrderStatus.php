<?php

namespace App\Enum;

enum OrderStatus: string
{
    case FORMED = 'formed';
    case PASSED = 'passed';
    case DELIVERED = 'delivered';
    case CANCEL = 'cancel';
}
