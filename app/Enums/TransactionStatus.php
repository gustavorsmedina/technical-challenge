<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case CREATED      = 'created';
    case SUCCESS      = 'success';
    case FAILED       = 'failed';
    case UNAUTHORIZED = 'unauthorized';

}
