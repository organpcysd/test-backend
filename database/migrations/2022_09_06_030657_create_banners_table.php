<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title')->comment('ชื่อแบนเนอร์');
            $table->boolean('publish')->default(1)->comment('เผยแพร่');
            $table->integer('sort')->default(0)->comment('ลำดับ');
            $table->timestamps();

            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
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
        Schema::dropIfExists('banners');
    }
}
