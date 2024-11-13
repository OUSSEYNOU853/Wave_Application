<?php

namespace App\Enums;

enum ActionCompte: string
{
    case DEPOT = 'Dépôt';
    case RETRAIT = 'Retrait';
    case UNBLOCK_LIMIT = 'Déplafonnement';
}
