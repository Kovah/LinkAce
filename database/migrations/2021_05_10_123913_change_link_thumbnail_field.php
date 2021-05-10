<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLinkThumbnailField extends Migration
{
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->text('thumbnail')->change();
        });
    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->string('thumbnail')->change();
        });
    }
}
