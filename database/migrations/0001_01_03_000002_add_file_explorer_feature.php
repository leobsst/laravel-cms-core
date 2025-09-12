<?php

use Laravel\Pennant\Migrations\PennantMigration;

return new class extends PennantMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('features')->updateOrInsert([
            'name' => 'file_explorer',
            'value' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('features')->where('name', 'file_explorer')->delete();
    }
};
