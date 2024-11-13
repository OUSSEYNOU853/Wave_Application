<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOT = 'DEPOT';
    case RETRAIT = 'RETRAIT';
    case TRANSFERT = 'TRANSFERT';
    case PAIEMENT = 'PAIEMENT';
}