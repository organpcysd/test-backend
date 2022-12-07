<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('ชื่อสินค้า');
            $table->double('price', 15, 8)->nullable()->comment('ราคา');
            $table->double('price_promotion', 15, 8)->nullable()->comment('ราคาโปรโมชั่น');
            $table->boolean('promotion_status')->default(0)->comment('สถานะโปรโมชั่น');
            $table->boolean('bestsell_status')->default(0)->comment('สถานะสินค้าขายดี');
            $table->boolean('recommended_status')->default(0)->comment('สถานะสินค้าแนะนำ');
            $table->string('slug');
            $table->text('detail')->nullable()->comment('รายละเอียดสินค้า');
            $table->text('short_detail')->nullable()->comment('รายละเอียดย่อยสินค้า');
            $table->string('seo_keyword')->nullable()->comment('Seo Keyword');
            $table->string('seo_description')->nullable()->comment('Seo Description');
            $table->boolean('publish')->default(1)->comment('เผยแพร่');
            $table->integer('sort')->default(0)->comment('ลำดับ');
            $table->timestamps();

            $table->unsignedBigInteger('website_id')->nullable()->comment('เว็บไซต์');
            $table->unsignedBigInteger('product_category_id')->nullalble()->comment('หมวดหมู่หลัก');
            $table->unsignedBigInteger('sub_product_category_id')->nullalble()->comment('หมวดหมู่ย่อย');

            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('sub_product_category_id')->references('id')->on('sub_product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
