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
        Schema::create('companies', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('slug');
            $table->string('tagline')->nullable();
            $table->text('about_us')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('website_url')->nullable();
            $table->text('address')->nullable();
            $table->text('maps_embed')->nullable();
            $table->string('company_logo')->nullable();
            $table->json('social_links')->nullable();
            $table->string('founder_name')->nullable();
            $table->date('foundation_date')->nullable();
            $table->string('industry')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('tin_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
