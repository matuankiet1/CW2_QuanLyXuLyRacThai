<?php

namespace App\Services;

use App\Models\User;
use App\Models\WasteLog;
use App\Models\CollectionSchedule;
use App\Models\UserReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Service: ShareSocialService
 * 
 * Mô tả: Xử lý logic chia sẻ thành tích phân loại rác lên mạng xã hội
 * - Tạo nội dung chia sẻ
 * - Tạo hình ảnh thành tích động
 * - Tạo URL chia sẻ cho các mạng xã hội
 */
class ShareSocialService
{
    /**
     * Lấy thống kê thành tích của student
     * 
     * @param User $user
     * @return array
     */
    public static function getStudentAchievements(User $user): array
    {
        // 1. Đếm số lần báo cáo
        $totalReports = UserReport::where('user_id', $user->user_id)->count();
        $resolvedReports = UserReport::where('user_id', $user->user_id)
            ->where('status', 'resolved')
            ->count();

        // 2. Tính lượng rác đã phân loại (nếu student có tham gia)
        // Lấy các collection schedules mà user là staff (nếu student cũng có thể là staff)
        $schedules = CollectionSchedule::where('staff_id', $user->user_id)->pluck('schedule_id');
        
        $wasteLogsStats = (object)[
            'total_logs' => 0,
            'total_weight' => 0,
            'waste_types_count' => 0
        ];
        
        if ($schedules->count() > 0) {
            $wasteLogsStats = WasteLog::whereIn('schedule_id', $schedules)
                ->select(
                    DB::raw('COUNT(*) as total_logs'),
                    DB::raw('COALESCE(SUM(waste_weight), 0) as total_weight'),
                    DB::raw('COUNT(DISTINCT waste_type_id) as waste_types_count')
                )
                ->first() ?? $wasteLogsStats;
        }

        // 3. Tính điểm thành tích (có thể mở rộng sau)
        $achievementScore = ($totalReports * 10) + ($resolvedReports * 20) + ($wasteLogsStats->total_logs * 5);

        return [
            'total_reports' => $totalReports,
            'resolved_reports' => $resolvedReports,
            'total_waste_logs' => $wasteLogsStats->total_logs,
            'total_waste_weight' => $wasteLogsStats->total_weight,
            'waste_types_count' => $wasteLogsStats->waste_types_count,
            'achievement_score' => $achievementScore,
        ];
    }

    /**
     * Tạo nội dung chia sẻ cho Facebook
     * 
     * @param User $user
     * @param array $achievements
     * @return string
     */
    public static function generateFacebookContent(User $user, array $achievements): string
    {
        $content = "🌱 Tôi đã tham gia bảo vệ môi trường cùng hệ thống Quản lý Xử lý Rác thải!\n\n";
        $content .= "📊 Thành tích của tôi:\n";
        $content .= "✅ {$achievements['total_reports']} báo cáo đã gửi\n";
        $content .= "✅ {$achievements['resolved_reports']} báo cáo đã được giải quyết\n";
        
        if ($achievements['total_waste_logs'] > 0) {
            $content .= "♻️ {$achievements['total_waste_logs']} lần phân loại rác\n";
            $content .= "♻️ " . number_format($achievements['total_waste_weight'], 2) . " kg rác đã phân loại\n";
        }
        
        $content .= "\n💚 Hãy cùng tôi lan tỏa tinh thần xanh!\n";
        $content .= "#BảoVệMôiTrường #PhânLoạiRác #TinhThầnXanh #GreenLife";

        return $content;
    }

    /**
     * Tạo nội dung chia sẻ cho Twitter/X
     * 
     * @param User $user
     * @param array $achievements
     * @return string
     */
    public static function generateTwitterContent(User $user, array $achievements): string
    {
        $content = "🌱 Tôi đã tham gia bảo vệ môi trường! ";
        $content .= "{$achievements['total_reports']} báo cáo, ";
        
        if ($achievements['total_waste_logs'] > 0) {
            $content .= number_format($achievements['total_waste_weight'], 2) . "kg rác đã phân loại. ";
        }
        
        $content .= "Hãy cùng lan tỏa tinh thần xanh! #BảoVệMôiTrường #TinhThầnXanh";

        return $content;
    }

    /**
     * Tạo URL chia sẻ cho Facebook
     * 
     * @param string $content
     * @param string|null $imageUrl
     * @return string
     */
    public static function getFacebookShareUrl(string $content, ?string $imageUrl = null): string
    {
        $url = "https://www.facebook.com/sharer/sharer.php?";
        $params = [
            'u' => urlencode(route('home')),
            'quote' => urlencode($content),
        ];
        
        if ($imageUrl) {
            $params['picture'] = urlencode($imageUrl);
        }
        
        return $url . http_build_query($params);
    }

    /**
     * Tạo URL chia sẻ cho Twitter/X
     * 
     * @param string $content
     * @param string|null $imageUrl
     * @return string
     */
    public static function getTwitterShareUrl(string $content, ?string $imageUrl = null): string
    {
        $url = "https://twitter.com/intent/tweet?";
        $params = [
            'text' => urlencode($content),
            'url' => urlencode(route('home')),
        ];
        
        return $url . http_build_query($params);
    }

    /**
     * Tạo URL chia sẻ cho LinkedIn
     * 
     * @param string $content
     * @param string|null $imageUrl
     * @return string
     */
    public static function getLinkedInShareUrl(string $content, ?string $imageUrl = null): string
    {
        $url = "https://www.linkedin.com/sharing/share-offsite/?";
        $params = [
            'url' => urlencode(route('home')),
        ];
        
        return $url . http_build_query($params);
    }

    /**
     * Tạo URL chia sẻ cho Zalo
     * 
     * @param string $content
     * @param string|null $imageUrl
     * @return string
     */
    public static function getZaloShareUrl(string $content, ?string $imageUrl = null): string
    {
        $url = "https://zalo.me/share?";
        $params = [
            'url' => urlencode(route('home')),
            'title' => urlencode("Thành tích bảo vệ môi trường của tôi"),
            'desc' => urlencode($content),
        ];
        
        return $url . http_build_query($params);
    }

    /**
     * Tạo URL chia sẻ cho WhatsApp
     * 
     * @param string $content
     * @return string
     */
    public static function getWhatsAppShareUrl(string $content): string
    {
        $url = "https://wa.me/?";
        $params = [
            'text' => urlencode($content . "\n\n" . route('home')),
        ];
        
        return $url . http_build_query($params);
    }

    /**
     * Tạo URL copy link
     * 
     * @return string
     */
    public static function getCopyLinkContent(): string
    {
        return route('home');
    }
}

