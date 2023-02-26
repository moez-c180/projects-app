<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_over_amounts', function (Blueprint $table) {
            $table->foreignId('membership_sheet_import_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_over_amounts', function (Blueprint $table) {
            $table->dropForeign(['membership_sheet_import_id']);
            $table->dropColumn('membership_sheet_import_id');
        });
    }
};
