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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('assigmentNumber');
            $table->string('name');
            $table->string('adusername');
            $table->string('email')->unique();
            $table->string('positionid');
            $table->string('positiontext');
            $table->string('personnelarea');
            $table->string('personnelareatext');
            $table->string('personnelsubarea');
            $table->string('personnelsubareatext');
            $table->string('departemenid');
            $table->string('departemenname1');
            $table->string('positionidsuperior');
            $table->string('companycode');
            $table->string('companyname');
            $table->integer('prl_plan');
            // $table->string('name');
            // $table->string('position');
            // $table->string('status');
            // $table->string('category');
            // $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->rememberToken();
            $table->timestamps();


            // $table->id();
            // $table->string('assigmentNumber');
            // $table->string('name');
            // $table->string('adusername');
            // $table->string('email')->unique();
            // $table->string('positionid');
            // $table->string('positiontext');
            // $table->string('personnelarea');
            // $table->string('personnelareatext');
            // $table->string('personnelsubarea');
            // $table->string('personnelsubareatext');
            // $table->string('departemenid');
            // $table->string('departemenname1');
            // $table->string('positionidsuperior');
            // $table->string('companycode');
            // $table->string('companyname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
