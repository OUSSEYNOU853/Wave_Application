<?php

namespace App\Interfaces;

use App\Models\AccountAction;

interface AccountActionServiceInterface
{
    public function processAction(array $data);
}
