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
        Schema::table('memberships', function (Blueprint $table) {
            $table->foreignId('membership_sheet_import_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('paid_amount');
            $table->boolean('approved')->default(0);
            $table->integer('membership_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign(['membership_sheet_import_id']);
            $table->dropColumn('membership_sheet_import_id');
            $table->dropColumn('paid_amount');
            $table->dropColumn('approved');
            $table->dropColumn('membership_value');
        });
    }
};
