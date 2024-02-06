<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->string('email');
            $table->timestamp('accepted_at')->nullable();
            $table->foreignIdFor(User::class, 'inviter_id');
            $table->foreignIdFor(User::class, 'created_user_id')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_invitations');
    }
};
