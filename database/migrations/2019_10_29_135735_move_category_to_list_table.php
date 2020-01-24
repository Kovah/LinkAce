<?php

use App\Models\Link;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveCategoryToListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, move child categories to list titles
        DB::table('categories')->whereNotNull('parent_category')->get()->each(function ($item) {
            $parent = DB::table('categories')->where('id', $item->parent_category)->first();
            DB::table('categories')->where('id', $item->id)->update([
                'name' => $parent->name . ' > ' . $item->name,
            ]);
        });

        // Move all corresponding tables
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('parent_category');
            $table->rename('lists');
        });

        Schema::create('link_lists', function (Blueprint $table) {
            $table->integer('link_id')->unsigned();
            $table->integer('list_id')->unsigned();

            $table->primary(['link_id', 'list_id']);
        });

        // Transfer all link relations form categories to categories
        DB::table('links')->whereNotNull('category_id')->get()->each(function ($link) {
            $list = DB::table('lists')->where('id', $link->category_id)->first();
            if (!empty($list)) {
                Link::withTrashed()->find($link->id)->lists()->attach($list->id);
            }
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->rename('categories');
        });

        Schema::dropIfExists('link_lists');
    }
}
