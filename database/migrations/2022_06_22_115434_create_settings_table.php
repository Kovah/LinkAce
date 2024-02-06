<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('settings', 'old_settings');

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();

            $table->string('group');
            $table->string('name');
            $table->boolean('locked')->default(false);
            $table->json('payload');

            $table->timestamps();

            $table->unique(['group', 'name']);
        });
    }

    public function down()
    {
        Schema::drop('settings');
        Schema::rename('old_settings', 'settings');
    }
};
