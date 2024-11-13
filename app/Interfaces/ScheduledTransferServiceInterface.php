<?php

namespace App\Interfaces;

interface ScheduledTransferServiceInterface
{
    public function scheduleTransfer(array $transferData);

    public function cancelScheduledTransfer($transferId);

    public function deleteInactiveTransfers();

    public function getActiveTransfers();

    public function getUserScheduledTransfers($userId);

    public function processScheduledTransfers();
}
