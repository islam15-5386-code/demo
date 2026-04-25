<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_name');
            $table->string('mime_type', 120)->nullable();
            $table->string('storage_disk')->default('public');
            $table->string('storage_path')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('assessment_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('passing_mark')->default(50);
            $table->timestamp('deadline_at')->nullable();
            $table->boolean('retake_allowed')->default(false);
            $table->unsignedInteger('max_attempts')->default(1);
            $table->json('rubric_config')->nullable();
            $table->timestamps();
        });

        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('ai_score')->nullable();
            $table->unsignedInteger('override_score')->nullable();
            $table->unsignedInteger('final_score')->nullable();
            $table->text('manual_feedback')->nullable();
            $table->string('grade_status')->default('pending');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('course_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('audience')->default('students');
            $table->string('status')->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('teacher_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('message_type')->default('feedback');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_messages');
        Schema::dropIfExists('course_announcements');
        Schema::dropIfExists('submission_reviews');
        Schema::dropIfExists('assessment_policies');
        Schema::dropIfExists('content_uploads');
    }
};
