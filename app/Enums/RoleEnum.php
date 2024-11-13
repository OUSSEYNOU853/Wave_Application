<?php

namespace App\Enums;

enum RoleEnum: string
{
    case CLIENT = 'client';
    case DISTRIBUTEUR = 'distributeur';
    case MARCHAND = 'marchand';
}
