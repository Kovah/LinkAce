<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('remember_token', function (Blueprint $table) {
                $table->string('sso_id')->nullable();
                $table->string('sso_provider')->nullable();
                $table->text('sso_token')->nullable();
                $table->text('sso_token_secret')->nullable();
                $table->text('sso_refresh_token')->nullable();
            });

            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sso_id',
                'sso_provider',
                'sso_token',
                'sso_token_secret',
                'sso_refresh_token',
            ]);
        });
    }
};
