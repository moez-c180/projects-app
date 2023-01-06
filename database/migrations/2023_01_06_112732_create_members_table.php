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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('military_number')->nullable();
            $table->string('seniority_number')->nullable();
            $table->foreignId('rank_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_institute_graduate')->default(0);
            $table->boolean('is_nco')->default(0);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_general_staff')->default(0);
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('home_phone_number')->nullable();
            $table->string('mobile_phone_number')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_title')->nullable();
            $table->string('class')->nullable();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->date('graduation_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('travel_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->date('pension_date')->nullable();
            $table->text('pension_reason')->nullable();
            $table->date('death_date')->nullable();
            $table->boolean('is_subscribed')->default(0);
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(['military_number', 'seniority_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
