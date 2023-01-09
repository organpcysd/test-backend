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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name')->nullable()->comment('ชื่อ');
            $table->boolean('status')->default(1)->comment('สถานะการใช้งาน (0, 1)');
            $table->string('username')->unique()->comment('ชื่อผู้ใช้');
            $table->string('phone')->nullable()->comment('เบอร์โทรศัพท์');
            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
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
