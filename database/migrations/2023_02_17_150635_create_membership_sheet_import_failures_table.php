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
        Schema::create('membership_sheet_import_failures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_sheet_import_id');
            $table->foreign('membership_sheet_import_id', 'membership_sheet_import_id_foreign')
                ->references('id')
                ->on('membership_sheet_imports')
                ->onDelete('cascade');

            $table->string('financial_branch_code');
            $table->string('unit_code');
            $table->string('member_military_number');
            $table->integer('amount');
            $table->json('reason');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_sheet_import_failures');
    }
};
