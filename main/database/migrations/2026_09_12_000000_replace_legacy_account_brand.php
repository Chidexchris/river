<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $legacy = 'TONA' . 'BANK';
        $brand = 'RIVERWIND';
        $replace = static function (string $table, string $column) use ($legacy, $brand): void {
            DB::table($table)
                ->where($column, 'like', "%{$legacy}%")
                ->update([$column => DB::raw("REPLACE({$column}, '{$legacy}', '{$brand}')")]);
        };

        DB::table('settings')->update(['account_number_prefix' => $brand]);
        $replace('users', 'account_number');
        $replace('users', 'ref_by');
        $replace('transactions', 'account_number');
        $replace('transactions', 'details');
        $replace('beneficiaries', 'details');
        $replace('money_transfers', 'wire_transfer_payload');
    }

    public function down(): void
    {
        // Account identifiers are not reverted because they are user-facing references.
    }
};
