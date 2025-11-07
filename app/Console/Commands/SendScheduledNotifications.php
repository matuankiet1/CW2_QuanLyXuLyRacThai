<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi các thông báo đã được hẹn giờ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu gửi thông báo đã hẹn giờ...');

        // Tìm các thông báo đã hẹn giờ và đã đến thời điểm gửi
        $scheduledNotifications = Notification::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($scheduledNotifications->isEmpty()) {
            $this->info('Không có thông báo nào cần gửi.');
            return Command::SUCCESS;
        }

        $this->info("Tìm thấy {$scheduledNotifications->count()} thông báo cần gửi.");

        $successCount = 0;
        $failCount = 0;

        foreach ($scheduledNotifications as $notification) {
            try {
                // Cập nhật trạng thái thông báo
                $notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $successCount++;
                $this->line("✓ Đã gửi thông báo: {$notification->title}");

                Log::info('Scheduled notification sent', [
                    'notification_id' => $notification->notification_id,
                    'title' => $notification->title,
                ]);

            } catch (\Exception $e) {
                $failCount++;
                $this->error("✗ Lỗi khi gửi thông báo: {$notification->title} - {$e->getMessage()}");

                Log::error('Failed to send scheduled notification', [
                    'notification_id' => $notification->notification_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Hoàn thành: {$successCount} thành công, {$failCount} thất bại.");

        return Command::SUCCESS;
    }
}
