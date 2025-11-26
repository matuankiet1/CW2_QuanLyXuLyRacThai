<?php

namespace App\Services;

use App\Models\User;
use App\Models\TrashRequest;
use Illuminate\Support\Facades\Log;

/**
 * Service tự động phân công nhân viên theo khu vực
 * 
 * Logic: Dựa trên location của yêu cầu để tìm nhân viên được phân công ở khu vực tương ứng
 */
class StaffAreaAssignmentService
{
    /**
     * Tự động tìm nhân viên phù hợp dựa trên location của yêu cầu
     * 
     * @param string $location Địa điểm của yêu cầu thu gom rác
     * @return User|null Nhân viên được phân công, hoặc null nếu không tìm thấy
     */
    public static function findStaffByLocation(string $location): ?User
    {
        try {
            // Lấy tất cả nhân viên có khu vực được phân công
            $staffs = User::where('role', 'staff')
                ->whereNotNull('assigned_area')
                ->where('assigned_area', '!=', '')
                ->get();

            if ($staffs->isEmpty()) {
                Log::warning("StaffAreaAssignmentService: No staff with assigned area found");
                return null;
            }

            // Chuẩn hóa location để so sánh (chuyển về lowercase, loại bỏ dấu)
            $normalizedLocation = self::normalizeLocation($location);

            // Tìm nhân viên có khu vực trùng khớp với location
            foreach ($staffs as $staff) {
                $normalizedArea = self::normalizeLocation($staff->assigned_area);
                
                // Kiểm tra xem location có chứa area hoặc area có chứa location không
                if (str_contains($normalizedLocation, $normalizedArea) || 
                    str_contains($normalizedArea, $normalizedLocation)) {
                    return $staff;
                }
            }

            // Nếu không tìm thấy trùng khớp chính xác, tìm theo từ khóa
            $locationKeywords = self::extractKeywords($normalizedLocation);
            
            foreach ($staffs as $staff) {
                $areaKeywords = self::extractKeywords(self::normalizeLocation($staff->assigned_area));
                
                // Kiểm tra xem có từ khóa nào trùng không
                $commonKeywords = array_intersect($locationKeywords, $areaKeywords);
                if (!empty($commonKeywords)) {
                    return $staff;
                }
            }

            Log::info("StaffAreaAssignmentService: No matching staff found for location", [
                'location' => $location
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("StaffAreaAssignmentService: Error finding staff", [
                'location' => $location,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Chuẩn hóa location để so sánh
     * 
     * @param string $location
     * @return string
     */
    private static function normalizeLocation(string $location): string
    {
        // Chuyển về lowercase
        $normalized = mb_strtolower($location, 'UTF-8');
        
        // Loại bỏ dấu tiếng Việt (có thể mở rộng thêm)
        $normalized = self::removeVietnameseAccents($normalized);
        
        // Loại bỏ khoảng trắng thừa
        $normalized = trim($normalized);
        
        return $normalized;
    }

    /**
     * Loại bỏ dấu tiếng Việt
     * 
     * @param string $str
     * @return string
     */
    private static function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        ];
        
        $noAccents = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];
        
        return str_replace($accents, $noAccents, $str);
    }

    /**
     * Trích xuất từ khóa từ location
     * 
     * @param string $location
     * @return array
     */
    private static function extractKeywords(string $location): array
    {
        // Tách thành các từ
        $words = preg_split('/[\s,\-]+/', $location);
        
        // Loại bỏ các từ quá ngắn hoặc không có ý nghĩa
        $keywords = array_filter($words, function($word) {
            return strlen($word) >= 3 && !in_array($word, ['khu', 'phuong', 'quan', 'thanh', 'pho', 'duong', 'ngach', 'hem']);
        });
        
        return array_values($keywords);
    }

    /**
     * Tự động phân công nhân viên cho nhiều yêu cầu dựa trên location
     * 
     * @param array $trashRequests Danh sách yêu cầu
     * @return array Mảng với key là request_id và value là staff được gán
     */
    public static function assignStaffsToRequests(array $trashRequests): array
    {
        $assignments = [];
        
        foreach ($trashRequests as $trashRequest) {
            $staff = self::findStaffByLocation($trashRequest->location);
            if ($staff) {
                $assignments[$trashRequest->request_id] = $staff;
            }
        }
        
        return $assignments;
    }
}

