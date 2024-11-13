<?php

namespace App\Enums;

enum StatutPayment: string
{
    case EN_ATTENTE = 'En attente';
    case TERMINER = 'Terminé';
    case ECHOUER = 'Échoué';
}
