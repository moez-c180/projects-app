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
        Schema::create('member_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->date('job_filled_date');
            $table->foreignId('position_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();
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
        Schema::dropIfExists('member_jobs');
    }
};
