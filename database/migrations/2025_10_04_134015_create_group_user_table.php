<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
Schema::create('group_user', function (Blueprint $table) {
$table->id();
$table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->string('role')->default('member'); // member | admin
$table->boolean('active')->default(true);
$table->timestamp('joined_at')->useCurrent();
$table->timestamps();


// enforce uniqueness (a user can only be joined once per group)
$table->unique(['group_id', 'user_id']);
});
}


public function down()
{
Schema::dropIfExists('group_user');
}
};
