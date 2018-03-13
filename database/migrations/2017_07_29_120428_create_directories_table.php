<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->increments('id');

            /**
             * Directory's name.
             * 
             * E.g. Atlantic Facilities and Research Equipment Database
             */
            $table->string('name')
                  ->unique();

            /**
             * Directory's acronym or nickname.
             * 
             * E.g. AFRED
             */
            $table->string('shortname')
                  ->index()
                  ->nullable();
            
            /**
             * Name of folder that contains all its resources (blade templates).
             * 
             * E.g. If it was set to "afred", then the API will look for its
             * related email resources in:
             * resources/views/emails/afred/...
             *                          ^
             *                      directory
             */
            $table->string('resource_folder')
                  ->unique();

            /**
             * Base URL (without forward slash) of its WordPress installation.
             * 
             * E.g. 'https://afred.ca'
             */
            $table->string('wp_base_url');

            /**
             * Base URL (without forward slash) of its WordPress administration
             * portal.
             * 
             * E.g. 'https://afred.ca/wp/wp-admin'
             */
            $table->string('wp_admin_base_url');

            /**
             * Base URL (without forward slash) of its WordPress API.
             * 
             * E.g. 'https://afred.ca/wp-json/wp/v2'
             */
            $table->string('wp_api_base_url');

            /**
             * WordPress API password.
             * 
             * This is a base64 encoded string of a WordPress username and
             * password. The API will use this for all WordPress API operations.
             * It is a good idea to limit the scope of this user account since
             * the credentials are stored in the database unencrypted.
             * 
             * @see https://en-ca.wordpress.org/plugins/application-passwords
             */
            $table->string('wp_api_password');

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
        Schema::dropIfExists('directories');
    }
}
