<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();

            $table->string('company_code')->nullable();
            $table->string('route_code')->nullable();
            $table->string('short_name')->unique();
            $table->string('full_name')->nullable(); // Manufacturer
            $table->string('company_address')->nullable();
            $table->srting('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('cont_person_one')->nullable();
            $table->string('contact_no_one')->unique();
            $table->string('email_one')->nullable(); 
            $table->string('desig_one')->nullable();
            $table->string('cont_person_two')->nullable();
            $table->string('contact_no_two')->nullable();
            $table->string('email_two')->nullable();
            $table->string('desig_two')->nullable();
             $table->string('cont_person_three')->nullable();
            $table->string('contact_no_three')->unique();
            $table->string('email_three')->nullable(); 
            $table->string('desig_three')->nullable();
             $table->string('cont_person_four')->nullable();
            $table->string('contact_no_four')->unique();
            $table->string('email_four')->nullable(); 
            $table->string('desig_four')->nullable();
             $table->string('group')->nullable();
            $table->string('zonal')->unique();
            $table->string('bill_type')->nullable(); // Manufacturer
            $table->string('c_from')->nullable();
            $table->srting('c_to')->nullable();
            $table->string('slock')->nullable();
            $table->string('lock_date')->nullable();


            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
