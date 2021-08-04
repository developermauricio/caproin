<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*=============================================
            CREANDO TABLA GENEROS ES OPCIONAL
        =============================================*/
//        Schema::create('genders', function (Blueprint $table){
//            $table->increments('id');
//            $table->json('name');
//            $table->json('description');
//            $table->timestamps();
//        });
        /*=============================================
            CREANDO TABLA DE USUARIOS
        =============================================*/
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('identification')->unique()->nullable();
            $table->unsignedBigInteger('identification_type_id')->nullable();
            $table->foreign('identification_type_id')->references('id')->on('identification_types');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
//            $table->text('biography')->nullable();
            $table->string('slug');
            $table->string('picture')->nullable();
            $table->string('password')->nullable();
//            $table->date('birthday')->nullable();
            $table->enum('state', [
                \App\User::ACTIVE,
                \App\User::INACTIVE
            ])->default(\App\User::ACTIVE);
//            $table->unsignedBigInteger('country_id')->nullable();
//            $table->foreign('country_id')->references('id')->on('countries');
//            $table->unsignedBigInteger('city_id')->nullable();
//            $table->foreign('city_id')->references('id')->on('cities');
//            $table->string('url_facebook')->nullable();
//            $table->string('url_youtube')->nullable();
//            $table->string('url_instagram')->nullable();
//            $table->string('url_tiktok')->nullable();
//            $table->string('url_twitter')->nullable();
//            $table->string('url_twitch')->nullable();
//            $table->string('url_spotify')->nullable();
//            $table->string('url_apple_music')->nullable();
//            $table->string('web_site')->nullable();
//            $table->unsignedInteger('gender_id')->nullable();
//            $table->foreign('gender_id')->references('id')->on('genders');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
