<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\IntegratedNotificationService;
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
    protected $signature = 'notifications:send-scheduled
                            {--dry-run : Chạy thử mà không thực sự gửi thông báo}
                            {--limit= : Giới hạn số lượng thông báo xử lý}';

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
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        if ($dryRun) {
            $this->warn('⚠️  Chế độ DRY-RUN: Không thực sự gửi thông báo');
        }

        $this->info('🔔 Bắt đầu gửi thông báo đã hẹn giờ...');

        // Tìm các thông báo đã hẹn giờ và đã đến thời điểm gửi
        $query = Notification::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->with('sender', 'recipients');

        if ($limit) {
            $query->limit($limit);
        }

        $scheduledNotifications = $query->get();

        if ($scheduledNotifications->isEmpty()) {
            $this->info('✅ Không có thông báo nào cần gửi.');
            return Command::SUCCESS;
        }

        $this->info("📋 Tìm thấy {$scheduledNotifications->count()} thông báo cần gửi.");

        $progressBar = $this->output->createProgressBar($scheduledNotifications->count());
        $progressBar->start();

        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;

        foreach ($scheduledNotifications as $notification) {
            try {
                // Kiểm tra xem có người nhận không
                if ($notification->recipients->isEmpty()) {
                    $this->newLine();
                    $this->warn("⚠️  Bỏ qua thông báo '{$notification->title}' - Không có người nhận");
                    $skippedCount++;
                    $progressBar->advance();
                    continue;
                }

                if (!$dryRun) {
                    // Gửi thông báo đến tất cả recipients
                    $recipientIds = $notification->recipients->pluck('user_id')->toArray();
                    $results = IntegratedNotificationService::sendToMany(
                        $recipientIds,
                        $notification->title,
                        $notification->content
                    );

                    // Cập nhật trạng thái thông báo
                    $notification->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);

                    Log::info('Scheduled notification sent', [
                        'notification_id' => $notification->notification_id,
                        'title' => $notification->title,
                        'recipients_count' => $results['total'],
                        'success_count' => $results['success'],
                        'failed_count' => $results['failed'],
                    ]);
                } else {
                    // Dry run: Chỉ log, không gửi
                    $this->newLine();
                    $this->line("📤 [DRY-RUN] Sẽ gửi: '{$notification->title}' đến {$notification->recipients->count()} người nhận");
                }

                $successCount++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $failCount++;
                $this->newLine();
                $this->error("❌ Lỗi khi gửi thông báo: {$notification->title}");
                $this->error("   Chi tiết: {$e->getMessage()}");

                Log::error('Failed to send scheduled notification', [
                    'notification_id' => $notification->notification_id,
                    'title' => $notification->title,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Hiển thị kết quả
        $this->info('📊 Kết quả:');
        $this->table(
            ['Loại', 'Số lượng'],
            [
                ['✅ Thành công', $successCount],
                ['❌ Thất bại', $failCount],
                ['⏭️  Bỏ qua', $skippedCount],
                ['📋 Tổng cộng', $scheduledNotifications->count()],
            ]
        );

        if ($dryRun) {
            $this->warn('⚠️  Đây là chế độ DRY-RUN. Không có thông báo nào thực sự được gửi.');
        }

        return Command::SUCCESS;
    }
}
