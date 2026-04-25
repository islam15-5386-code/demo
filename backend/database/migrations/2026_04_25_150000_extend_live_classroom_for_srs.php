<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_classes', function (Blueprint $table) {
            if (! Schema::hasColumn('live_classes', 'room_slug')) {
                $table->string('room_slug')->nullable()->after('provider');
            }

            if (! Schema::hasColumn('live_classes', 'reminder_24h_sent')) {
                $table->boolean('reminder_24h_sent')->default(false)->after('reminder_24h');
            }

            if (! Schema::hasColumn('live_classes', 'reminder_1h_sent')) {
                $table->boolean('reminder_1h_sent')->default(false)->after('reminder_24h_sent');
            }
        });

        Schema::table('live_class_recordings', function (Blueprint $table) {
            if (! Schema::hasColumn('live_class_recordings', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable()->after('recording_url');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('tenant_id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message');
            }

            if (! Schema::hasColumn('notifications', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('is_read');
            }
        });

        $liveClasses = DB::table('live_classes')
            ->select('live_classes.id', 'live_classes.meeting_url', 'live_classes.title', 'live_classes.start_at', 'live_classes.scheduled_at', 'courses.tenant_id')
            ->leftJoin('courses', 'courses.id', '=', 'live_classes.course_id')
            ->get();

        foreach ($liveClasses as $liveClass) {
            $roomSlug = null;

            if (! empty($liveClass->meeting_url)) {
                $roomSlug = Str::afterLast($liveClass->meeting_url, '/');
            }

            if (! $roomSlug) {
                $roomSlug = Str::slug($liveClass->title ?: ('live-class-' . $liveClass->id)) . '-' . Str::lower(Str::random(6));
            }

            DB::table('live_classes')
                ->where('id', $liveClass->id)
                ->update([
                    'tenant_id' => $liveClass->tenant_id,
                    'scheduled_at' => $liveClass->scheduled_at ?? $liveClass->start_at,
                    'room_slug' => $roomSlug,
                ]);
        }

        DB::table('live_class_recordings')
            ->whereNull('duration_seconds')
            ->whereNotNull('duration')
            ->update([
                'duration_seconds' => DB::raw('duration'),
            ]);

        DB::table('notifications')
            ->whereNull('title')
            ->update([
                'title' => 'Smart LMS Notification',
            ]);
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $columns = [
                Schema::hasColumn('notifications', 'user_id') ? 'user_id' : null,
                Schema::hasColumn('notifications', 'title') ? 'title' : null,
                Schema::hasColumn('notifications', 'is_read') ? 'is_read' : null,
                Schema::hasColumn('notifications', 'sent_at') ? 'sent_at' : null,
            ];

            $columns = array_values(array_filter($columns));

            if (in_array('user_id', $columns, true)) {
                $table->dropConstrainedForeignId('user_id');
                $columns = array_values(array_diff($columns, ['user_id']));
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('live_class_recordings', function (Blueprint $table) {
            if (Schema::hasColumn('live_class_recordings', 'duration_seconds')) {
                $table->dropColumn('duration_seconds');
            }
        });

        Schema::table('live_classes', function (Blueprint $table) {
            $columns = [
                Schema::hasColumn('live_classes', 'room_slug') ? 'room_slug' : null,
                Schema::hasColumn('live_classes', 'reminder_24h_sent') ? 'reminder_24h_sent' : null,
                Schema::hasColumn('live_classes', 'reminder_1h_sent') ? 'reminder_1h_sent' : null,
            ];

            $columns = array_values(array_filter($columns));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
