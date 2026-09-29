<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('prev_slug')->nullable();
            $table->text('short')->nullable();
            $table->text('views')->default(0);
            $table->longText('description')->nullable();
            $table->longText('description_1')->nullable();
            $table->longText('description_2')->nullable();
            $table->longText('description_3')->nullable();
            $table->json('features')->nullable();
            $table->json('extra')->nullable();
            $table->text('url')->nullable();
            $table->string('location')->nullable();
            $table->string('img_path')->nullable();
            $table->json('img_paths')->nullable();
            $table->string('video_path')->nullable();
            $table->json('video_paths')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            // SEO meta fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('admin_approved')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            $table->timestamp('trashed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
