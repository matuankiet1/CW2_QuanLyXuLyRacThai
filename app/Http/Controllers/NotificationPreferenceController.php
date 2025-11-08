<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationPreferenceController extends Controller
{
    /**
     * Hiển thị form quản lý preferences
     */
    public function index()
    {
        $user = Auth::user();
        
        $preference = $user->preference ?? NotificationPreference::updateOrCreateForUser($user->user_id);

        return view('user.notification-preferences.index', compact('preference'));
    }

    /**
     * Cập nhật preferences
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email' => 'sometimes|boolean',
            'push' => 'sometimes|boolean',
            'in_app' => 'sometimes|boolean',
        ]);

        try {
            $preference = NotificationPreference::updateOrCreateForUser(
                $user->user_id,
                $validated
            );

            Log::info('Notification preferences updated', [
                'user_id' => $user->user_id,
                'preferences' => $validated,
            ]);

            return back()->with('success', 'Cập nhật cài đặt thông báo thành công!');
        } catch (\Exception $e) {
            Log::error('Failed to update notification preferences', [
                'user_id' => $user->user_id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật cài đặt.');
        }
    }
}
