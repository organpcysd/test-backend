<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('ชื่อเว็บไซต์');
            $table->string('domain_name')->nullable()->comment('ชื่อโดเมน');
            $table->string('title')->nullable()->comment('ไตเติ้ลเว็บไซต์');
            $table->string('address')->nullable()->comment('ที่อยู่');
            $table->string('phone1')->nullable()->comment('เบอร์โทรศัพท์ 1');
            $table->string('phone2')->nullable()->comment('เบอร์โทรศัพท์ 2');
            $table->string('fax')->nullable()->comment('เบอร์แฟกซ์');
            $table->string('company_number')->nullable()->comment('เบอร์บริษัท');
            $table->string('email1')->nullable()->comment('อีเมล 1');
            $table->string('email2')->nullable()->comment('อีเมล 2');
            $table->string('line')->nullable()->comment('ไลน์');
            $table->string('line_token')->nullable()->comment('ไลน์โทเค็น (Line notify)');
            $table->string('facebook')->nullable()->comment('facebook fanpage');
            $table->string('messenger')->nullable()->comment('facebook messenger');
            $table->string('youtube')->nullable()->comment('youtube channel');
            $table->string('youtube_embed')->nullable()->comment('youtube link (embed)');
            $table->string('instagram')->nullable()->comment('instagram');
            $table->string('twitter')->nullable()->comment('ทวิตเตอร์');
            $table->string('linkedin')->nullable()->comment('LinkedIn');
            $table->string('whatsapp')->nullable()->comment('WhatsApp');
            $table->string('google_map')->nullable()->comment('หมุดที่อยู่บริษัท');
            $table->longText('about_us')->nullable()->comment('เกี่ยวกับเรา');
            $table->text('short_about_us')->nullable()->comment('เกี่ยวกับเรา (แบบย่อ)');
            $table->text('seo_keyword')->nullable()->comment('seo keyword');
            $table->text('seo_description')->nullable()->comment('seo description');

            $table->timestamps();
        });

        Schema::create('website_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('ชื่อเว็บไซต์');
            $table->string('address')->nullable()->comment('ที่อยู่');
            $table->string('phone')->nullable()->comment('เบอร์โทรศัพท์');
            $table->string('email')->nullable()->comment('อีเมล');
            $table->string('line')->nullable()->comment('ไลน์');
            $table->string('line_token')->nullable()->comment('ไลน์โทเค็น (Line notify)');
            $table->string('facebook')->nullable()->comment('facebook fanpage');
            $table->string('messenger')->nullable()->comment('facebook messenger');
            $table->string('google_map')->nullable()->comment('หมุดที่อยู่บริษัท');
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
        Schema::dropIfExists('websites');
        Schema::dropIfExists('website_branches');
    }
}
