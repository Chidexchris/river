<?php

namespace App\Interfaces;

use Carbon\Carbon;

interface HasInstallments
{
    /**
     * Get the start date of the installment schedule.
     *
     * @return Carbon|null
     */
    public function getInstallmentStartDate(): ?Carbon;

    /**
     * Get the interval in days between each installment.
     *
     * @return int
     */
    public function getInstallmentInterval(): int;

    /**
     * Get the total number of installments.
     *
     * @return int
     */
    public function getTotalInstallments(): int;

    /**
     * Get the specific installment date.
     *
     * @return Carbon
     */
    public function getInstallmentDate(): Carbon;
}
