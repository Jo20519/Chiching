<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('withdrawal_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->decimal('amount', 15, 2);
    $table->text('reason')->nullable();
    $table->string('status')->default('pending'); // pending/approved/rejected
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
    });
    }
    
    
    public function down()
    {
    Schema::dropIfExists('withdrawal_requests');
    }
    };
