<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('ชื่อหมวดหมู่');
            $table->string('detail')->nullable()->comment('รายละเอียด');
            $table->string('slug');
            $table->boolean('publish')->default(1)->comment('เผยแพร่');
            $table->integer('sort')->default(0)->comment('ลำดับ');
            $table->timestamps();

            $table->unsignedBigInteger('parent_id')->nullable()->comment('หมวดหมู่');
            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        // Schema::create('product_categories', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title')->comment('ชื่อหมวดหมู่');
        //     $table->string('detail')->nullable()->comment('รายละเอียด');
        //     $table->string('slug');
        //     $table->boolean('publish')->default(1)->comment('เผยแพร่');
        //     $table->integer('sort')->default(0)->comment('ลำดับ');
        //     $table->timestamps();

        //     $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
        //     $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        // });

        // Schema::create('sub_product_categories', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title')->comment('ชื่อหมวดหมู่ย่อย');
        //     $table->string('detail')->nullable()->comment('รายละเอียด');
        //     $table->string('slug');
        //     $table->boolean('publish')->default(1)->comment('เผยแพร่');
        //     $table->integer('sort')->default(0)->comment('ลำดับ');
        //     $table->timestamps();

        //     $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
        //     $table->unsignedBigInteger('product_category_id')->nullable()->comment('หมวดหมู่');

        //     $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        //     $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
        // Schema::dropIfExists('sub_product_categories');
    }
}
