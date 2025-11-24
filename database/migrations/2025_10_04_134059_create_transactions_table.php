<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('type'); // contribution/withdrawal/admin_adjustment
    $table->decimal('amount', 15, 2);
    $table->string('reference')->nullable();
    $table->timestamps();
    });
    }
    
    
    public function down()
    {
    Schema::dropIfExists('transactions');
    }
    };