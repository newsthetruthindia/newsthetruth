<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->boolean('create_user')->default(0);
            $table->boolean('edit_user')->default(0);
            $table->boolean('view_user')->default(0);
            $table->boolean('delete_user')->default(0);

            $table->boolean('create_post')->default(0);
            $table->boolean('view_post_list')->default(1);
            $table->boolean('edit_post')->default(0);
            $table->boolean('delete_post')->default(0);
            $table->boolean('publish_post')->default(0);
            $table->boolean('review_post')->default(0);
            $table->boolean('set_post_priority')->default(0);
            $table->boolean('update_post_seo')->default(0);
            $table->boolean('add_post_comment')->default(1);
            $table->boolean('edit_others_post_comment')->default(0);
            $table->boolean('edit_post_comment')->default(1);
            $table->boolean('delete_post_comment')->default(1);
            $table->boolean('delete_others_post_comment')->default(0);

            $table->boolean('create_page')->default(0);
            $table->boolean('view_page_list')->default(1);
            $table->boolean('edit_page')->default(0);
            $table->boolean('delete_page')->default(0);

            $table->boolean('create_gallery')->default(0);
            $table->boolean('update_gallery')->default(0);
            $table->boolean('delete_gallery')->default(0);

            $table->boolean('create_category')->default(0);
            $table->boolean('edit_category')->default(0);
            $table->boolean('delete_category')->default(0);

            $table->boolean('create_tag')->default(0);
            $table->boolean('edit_tag')->default(0);
            $table->boolean('delete_tag')->default(0);

            $table->boolean('parmanant_delete')->default(0);
            $table->boolean('view_settings')->default(0);
            $table->boolean('update_settings')->default(0);
            $table->boolean('manage_user_settings')->default(0);
            $table->boolean('manage_site_settings')->default(0);
            $table->boolean('manage_post_settings')->default(0);
            $table->boolean('manage_category_settings')->default(0);
            $table->boolean('manage_other_settings')->default(0);
            $table->boolean('manage_menu')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
};
