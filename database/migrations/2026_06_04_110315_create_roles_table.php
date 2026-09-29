<?php

// ============================================================
// Run order:
// 1. create_roles_table
// 2. create_permissions_table
// 3. create_role_permissions_table
// 4. create_user_roles_table
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ── 1. roles ────────────────────────────────────────────────
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            // Role name e.g. "Super Admin", "Editor"
            $table->string('name')->unique();

            // URL-friendly key e.g. "super-admin", "editor"
            $table->string('slug')->unique();

            // Optional description
            $table->string('description')->nullable();

            // Super admin flag — bypasses all permission checks
            $table->boolean('is_super_admin')->default(false);

            $table->timestamps();
        });

        // ── 2. permissions ───────────────────────────────────
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            // Module name e.g. "blogs", "projects"
            $table->string('module');

            // Action e.g. "view", "create", "edit", "delete"
            $table->string('action');

            // Combined key e.g. "blogs.create" — used in checks
            $table->string('key')->unique();

            // Human-readable label e.g. "Blog - Create"
            $table->string('label')->nullable();

            $table->timestamps();

            $table->index('module');
            $table->index('key');
        });

        // ── 3. role_permissions (pivot) ──────────────────────
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            // Prevent duplicate assignments
            $table->unique(['role_id', 'permission_id']);

            $table->timestamps();
        });

        // ── 4. user_roles (pivot) ────────────────────────────
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->unique(['user_id', 'role_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};