<?php

namespace App\Console\Commands;

use App\Models\LiveClass;
use App\Models\Notification;
use App\Models\User;
use App\Support\LmsSupport;
use Illuminate\Console\Command;

class SendLiveClassReminders extends Command
{
    protected $signature = 'live-classes:send-reminders';

    protected $description = 'Send 24-hour and 1-hour reminders for scheduled live classes';

    public function handle(): int
    {
        $now = now();

        $classesFor24h = LiveClass::query()
            ->with('course:id,tenant_id')
            ->where('status', 'scheduled')
            ->where('reminder_24h_sent', false)
            ->whereBetween('scheduled_at', [
                $now->copy()->addHours(23),
                $now->copy()->addHours(25),
            ])
            ->get();

        foreach ($classesFor24h as $liveClass) {
            $this->sendReminder($liveClass, '24h');

            $liveClass->update([
                'reminder_24h_sent' => true,
            ]);
        }

        $classesFor1h = LiveClass::query()
            ->with('course:id,tenant_id')
            ->where('status', 'scheduled')
            ->where('reminder_1h_sent', false)
            ->whereBetween('scheduled_at', [
                $now->copy()->addMinutes(55),
                $now->copy()->addMinutes(65),
            ])
            ->get();

        foreach ($classesFor1h as $liveClass) {
            $this->sendReminder($liveClass, '1h');

            $liveClass->update([
                'reminder_1h_sent' => true,
            ]);
        }

        return self::SUCCESS;
    }

    private function sendReminder(LiveClass $liveClass, string $type): void
    {
        $students = User::query()
            ->where('tenant_id', $liveClass->tenant_id)
            ->where('role', 'student')
            ->whereHas('enrollments', function ($query) use ($liveClass) {
                $query->where('course_id', $liveClass->course_id);
            })
            ->get();

        foreach ($students as $student) {
            $payload = [
                'tenant_id' => $liveClass->tenant_id,
                'audience' => 'Student',
                'type' => 'live_class_reminder',
                'message' => "Your live class {$liveClass->title} starts in {$type}.",
            ];

            if (LmsSupport::notificationHasColumn('user_id')) {
                $payload['user_id'] = $student->id;
            }

            if (LmsSupport::notificationHasColumn('title')) {
                $payload['title'] = 'Live Class Reminder';
            }

            if (LmsSupport::notificationHasColumn('is_read')) {
                $payload['is_read'] = false;
            }

            if (LmsSupport::notificationHasColumn('sent_at')) {
                $payload['sent_at'] = now();
            }

            Notification::query()->create($payload);
        }
    }
}
