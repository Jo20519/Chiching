<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('groups', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
    $table->decimal('contribution_amount', 15, 2)->nullable();
    $table->timestamps();
    });
    }
    
    
    public function down()
    {
    Schema::dropIfExists('groups');
    }
    };