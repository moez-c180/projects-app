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
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('bank_name_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('bank_branch_name')->nullable();
            $table->string('register_number')->nullable();
            $table->string('file_number')->nullable();
            $table->string('review')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeignIdFor('bank_name_id');
            $table->dropForeign(['bank_name_id']);
            $table->dropColumn('bank_branch_name');
            $table->dropColumn('register_number');
            $table->dropColumn('file_number');
            $table->dropColumn('review');
        });
    }
};
