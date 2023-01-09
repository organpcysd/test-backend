<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title')->comment('หมวดหมู่คำถาม');
            $table->boolean('publish')->nullable()->default(1)->comment('เผยแพร่');
            $table->integer('sort')->nullable()->default(0)->comment('ลำดับ');

            $table->timestamps();

            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('question')->comment('คำถาม');
            $table->longText('answer')->comment('คำตอบ');
            $table->boolean('publish')->nullable()->default(1)->comment('เผยแพร่');
            $table->integer('sort')->nullable()->default(0)->comment('ลำดับ');

            $table->timestamps();

            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
            $table->unsignedBigInteger('faq_category_id')->nullable()->comment('หมวดหมู่คำถาม');

            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('faq_category_id')->references('id')->on('faq_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('faqs');
    }
}
