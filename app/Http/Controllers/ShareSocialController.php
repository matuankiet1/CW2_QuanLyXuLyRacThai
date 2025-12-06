<?php

namespace App\Http\Controllers;

use App\Services\ShareSocialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller: ShareSocialController
 * 
 * Mô tả: Xử lý các request liên quan đến chia sẻ thành tích lên mạng xã hội
 * - Hiển thị trang chia sẻ
 * - Tạo nội dung chia sẻ
 * - Redirect đến các mạng xã hội
 */
class ShareSocialController extends Controller
{
    /**
     * Hiển thị trang chia sẻ thành tích
     * Route: GET /student/share-achievements
     */
    public function showSharePage()
    {
        $user = Auth::user();
        
        // Chỉ cho phép student
        if (!$user || !$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        // Lấy thành tích của student
        $achievements = ShareSocialService::getStudentAchievements($user);

        // Tạo nội dung chia sẻ cho các mạng xã hội
        $facebookContent = ShareSocialService::generateFacebookContent($user, $achievements);
        $twitterContent = ShareSocialService::generateTwitterContent($user, $achievements);

        // Tạo URL chia sẻ
        $shareUrls = [
            'facebook' => ShareSocialService::getFacebookShareUrl($facebookContent),
            'twitter' => ShareSocialService::getTwitterShareUrl($twitterContent),
            'linkedin' => ShareSocialService::getLinkedInShareUrl($facebookContent),
            'zalo' => ShareSocialService::getZaloShareUrl($facebookContent),
            'whatsapp' => ShareSocialService::getWhatsAppShareUrl($facebookContent),
            'copy_link' => ShareSocialService::getCopyLinkContent(),
        ];

        return view('student.share-achievements', compact(
            'user',
            'achievements',
            'facebookContent',
            'twitterContent',
            'shareUrls'
        ));
    }

    /**
     * API: Lấy thành tích của student (JSON)
     * Route: GET /api/student/achievements
     */
    public function getAchievements()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập.'
            ], 403);
        }

        $achievements = ShareSocialService::getStudentAchievements($user);
        $facebookContent = ShareSocialService::generateFacebookContent($user, $achievements);
        $twitterContent = ShareSocialService::generateTwitterContent($user, $achievements);

        return response()->json([
            'success' => true,
            'achievements' => $achievements,
            'content' => [
                'facebook' => $facebookContent,
                'twitter' => $twitterContent,
            ],
            'share_urls' => [
                'facebook' => ShareSocialService::getFacebookShareUrl($facebookContent),
                'twitter' => ShareSocialService::getTwitterShareUrl($twitterContent),
                'linkedin' => ShareSocialService::getLinkedInShareUrl($facebookContent),
                'zalo' => ShareSocialService::getZaloShareUrl($facebookContent),
                'whatsapp' => ShareSocialService::getWhatsAppShareUrl($facebookContent),
            ]
        ]);
    }

    /**
     * Redirect đến mạng xã hội để chia sẻ
     * Route: GET /student/share/{platform}
     */
    public function shareToPlatform(string $platform)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $achievements = ShareSocialService::getStudentAchievements($user);
        $facebookContent = ShareSocialService::generateFacebookContent($user, $achievements);
        $twitterContent = ShareSocialService::generateTwitterContent($user, $achievements);

        $url = match($platform) {
            'facebook' => ShareSocialService::getFacebookShareUrl($facebookContent),
            'twitter', 'x' => ShareSocialService::getTwitterShareUrl($twitterContent),
            'linkedin' => ShareSocialService::getLinkedInShareUrl($facebookContent),
            'zalo' => ShareSocialService::getZaloShareUrl($facebookContent),
            'whatsapp' => ShareSocialService::getWhatsAppShareUrl($facebookContent),
            default => route('student.share-achievements'),
        };

        return redirect($url);
    }
}

