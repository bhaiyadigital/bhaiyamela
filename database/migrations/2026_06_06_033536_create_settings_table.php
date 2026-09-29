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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
             // Site identity
            $table->string('site_name')->nullable();
            $table->string('site_slogan')->nullable();
            $table->string('logo')->nullable();           
            $table->string('favicon')->nullable();   
 
            // SEO — global default
            $table->string('meta_index')->default('index');  // 'index' or 'noindex'
 
            // Legal pages
            $table->string('privacy_policy_url')->nullable();
            $table->string('terms_url')->nullable();
 
            // Footer
            $table->string('footer_credit')->nullable();  
 
            // Contact info
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
 
            // Google Map embed
            $table->text('map_embed')->nullable();
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
