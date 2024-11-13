<?php

namespace App\Enums;

enum StatutTransaction: string
{
    case TERMINER = 'Terminé';
    case ECHOUER = 'Échoué';
    case ANNULER = 'Annulé';
}
