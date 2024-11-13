<?php

namespace App\Enums;

enum StatutTransfert: string
{
    case EN_ATTENTE = 'En attente';
    case TERMINER = 'Terminé';
    case ANNULER = 'Annulé';
}
