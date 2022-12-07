<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('หัวข้อ');
            $table->string('slug');
            $table->text('short_detail')->nullable()->comment('รายละเอียดย่อย');
            $table->text('detail')->nullable()->comment('รายละเอียด');
            $table->string('seo_keyword')->nullable()->comment('Seo Keyword');
            $table->string('seo_description')->nullable()->comment('Seo Description');
            $table->boolean('publish')->nullable()->default(1)->comment('เผยแพร่');
            $table->integer('sort')->nullable()->default(0)->comment('ลำดับ');
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
        Schema::dropIfExists('news');
    }
}
