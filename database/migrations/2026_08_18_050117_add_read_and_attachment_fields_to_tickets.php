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
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('is_read_admin')->default(false)->after('status'); // এডমিন পড়েছে কিনা
            $table->boolean('is_read_user')->default(true)->after('is_read_admin'); // ইউজার পড়েছে কিনা
            $table->string('attachment')->nullable()->after('description'); // টিকিটের মূল ফাইল
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('message'); // রিপ্লাইয়ের ফাইল
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'is_read_admin',
                'is_read_user',
                'attachment',
            ]);
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
