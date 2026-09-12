<?php

namespace App\Constants;

class ManageStatus
{
    // General Active/Inactive Status
    const int INACTIVE = 0;
    const int ACTIVE   = 1;

    // Yes/No Status
    const int NO  = 0;
    const int YES = 1;

    // Verification Status
    const int UNVERIFIED = 0;
    const int VERIFIED   = 1;
    const int PENDING    = 2;

    // Payment Status
    const int PAYMENT_INITIATE = 0;
    const int PAYMENT_SUCCESS  = 1;
    const int PAYMENT_PENDING  = 2;
    const int PAYMENT_CANCEL   = 3;

    // Branch Staff Status
    const int BRANCH_MANAGER         = 1;
    const int BRANCH_ACCOUNT_OFFICER = 2;

    // Authorization Mode Status
    const int AUTHORIZATION_MODE_EMAIL = 1;
    const int AUTHORIZATION_MODE_SMS   = 2;

    // Money Transfer Status
    const int MONEY_TRANSFER_FAILED    = 0;
    const int MONEY_TRANSFER_COMPLETED = 1;
    const int MONEY_TRANSFER_PENDING   = 2;

    // DPS Status
    const int DPS_CLOSED  = 0;
    const int DPS_RUNNING = 1;
    const int DPS_MATURED = 2;

    // FDS Status
    const int FDS_CLOSED  = 0;
    const int FDS_RUNNING = 1;

    // Loan Status
    const int LOAN_REJECTED = 0;
    const int LOAN_RUNNING  = 1;
    const int LOAN_PAID     = 2;
    const int LOAN_PENDING  = 3;
}
