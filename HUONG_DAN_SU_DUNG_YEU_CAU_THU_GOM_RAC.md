# 📖 Hướng dẫn sử dụng chức năng Yêu cầu thu gom rác

## 🎯 Tổng quan

Hệ thống quản lý yêu cầu thu gom rác với 3 vai trò: **Student**, **Staff**, và **Admin**. Luồng hoạt động tự động từ khi student gửi yêu cầu đến khi admin duyệt.

## 🔄 Luồng hoạt động (State Machine)

```
pending → assigned → staff_done → waiting_admin → admin_approved / admin_rejected
                                                          ↓
                                                    (staff có thể cập nhật lại)
```

### Giải thích các trạng thái:

-   **pending**: Đang chờ hệ thống gán staff
-   **assigned**: Đã được gán cho staff, đang chờ staff xử lý
-   **staff_done**: Staff đã hoàn thành và cập nhật thông tin
-   **waiting_admin**: Đang chờ admin duyệt
-   **admin_approved**: Admin đã duyệt thành công ✅
-   **admin_rejected**: Admin từ chối, staff có thể cập nhật lại ❌

---

## 👨‍🎓 STUDENT - Sinh viên

### 1. Tạo yêu cầu thu gom rác mới

**Bước 1:** Đăng nhập với tài khoản Student

**Bước 2:** Truy cập menu "Yêu cầu thu gom rác" hoặc vào đường dẫn:

```
http://your-domain/student/trash-requests
```

**Bước 3:** Click nút **"Tạo yêu cầu mới"** hoặc vào:

```
http://your-domain/student/trash-requests/create
```

**Bước 4:** Điền form:

-   **Địa điểm thu gom** ⭐ (bắt buộc): Ví dụ: "Khu A, Tòa nhà B, Phòng 101"
-   **Loại rác** ⭐ (bắt buộc): Ví dụ: "Rác tái chế", "Rác hữu cơ", "Rác thải điện tử"
-   **Mô tả chi tiết** (tùy chọn): Mô tả về loại rác, số lượng, tình trạng...
-   **Ảnh minh chứng** (tùy chọn): Upload ảnh (JPG, PNG, WEBP, tối đa 5MB)

**Bước 5:** Click **"Gửi yêu cầu"**

**Kết quả:**

-   Hệ thống tự động gán staff có ít nhiệm vụ nhất
-   Yêu cầu chuyển sang trạng thái "assigned"
-   Bạn sẽ nhận thông báo về staff được gán

### 2. Xem danh sách yêu cầu của mình

**Truy cập:** `http://your-domain/student/trash-requests`

**Tính năng:**

-   Xem tất cả yêu cầu đã gửi
-   Lọc theo trạng thái: Tất cả, Đang chờ, Đã gán, Chờ duyệt, Đã duyệt, Bị từ chối
-   Xem thông tin staff được gán
-   Xem trạng thái hiện tại

### 3. Xem chi tiết yêu cầu

**Truy cập:** Click vào "Xem chi tiết" hoặc vào:

```
http://your-domain/student/trash-requests/{id}
```

**Thông tin hiển thị:**

-   Thông tin yêu cầu (địa điểm, loại rác, mô tả)
-   Ảnh minh chứng đã upload
-   Thông tin staff được gán
-   Ảnh và ghi chú từ staff (nếu có)
-   Phản hồi từ admin (nếu đã duyệt/từ chối)

---

## 👷 STAFF - Nhân viên

### 1. Xem danh sách nhiệm vụ được gán

**Bước 1:** Đăng nhập với tài khoản Staff

**Bước 2:** Truy cập:

```
http://your-domain/staff/trash-requests
```

**Tính năng:**

-   Xem tất cả nhiệm vụ được gán cho bạn
-   Lọc theo trạng thái: Tất cả, Đã gán, Đã hoàn thành, Chờ duyệt, Bị từ chối
-   Xem thông tin người gửi và yêu cầu
-   Nút "Cập nhật" chỉ hiển thị khi trạng thái là "assigned" hoặc "admin_rejected"

### 2. Xem chi tiết nhiệm vụ

**Truy cập:** Click vào "Xem chi tiết" hoặc vào:

```
http://your-domain/staff/trash-requests/{id}
```

**Thông tin hiển thị:**

-   Thông tin đầy đủ về yêu cầu
-   Ảnh minh chứng từ student
-   Thông tin bạn đã cập nhật (nếu có)
-   Phản hồi từ admin (nếu có)

### 3. Cập nhật nhiệm vụ (Hoàn thành)

**Bước 1:** Vào chi tiết nhiệm vụ có trạng thái "assigned" hoặc "admin_rejected"

**Bước 2:** Click nút **"Cập nhật"** hoặc vào:

```
http://your-domain/staff/trash-requests/{id}/edit
```

**Bước 3:** Điền form:

-   **Ảnh minh chứng** ⭐ (bắt buộc nếu chưa có): Upload ảnh sau khi thu gom
-   **Ghi chú** (tùy chọn): Ghi chú về quá trình thu gom, số lượng, tình trạng...

**Bước 4:** Click **"Hoàn thành nhiệm vụ"**

**Kết quả:**

-   Nhiệm vụ tự động chuyển sang "waiting_admin"
-   Admin sẽ nhận thông báo để duyệt
-   Bạn có thể xem lại thông tin đã cập nhật

**Lưu ý:**

-   Nếu bị admin từ chối, bạn có thể cập nhật lại với thông tin mới
-   Sau khi cập nhật, nhiệm vụ lại chuyển sang "waiting_admin"

---

## 👨‍💼 ADMIN - Quản trị viên

### 1. Xem danh sách tất cả yêu cầu

**Bước 1:** Đăng nhập với tài khoản Admin

**Bước 2:** Truy cập:

```
http://your-domain/admin/trash-requests
```

**Tính năng:**

-   **Thống kê:** Tổng yêu cầu, Chờ duyệt, Đã duyệt
-   **Tìm kiếm:** Theo địa điểm, loại rác, tên người gửi, tên staff
-   **Lọc:** Theo trạng thái
-   **Xem:** Tất cả thông tin trong bảng

### 2. Xem chi tiết yêu cầu

**Truy cập:** Click vào "Xem" hoặc vào:

```
http://your-domain/admin/trash-requests/{id}
```

**Thông tin hiển thị:**

-   Thông tin đầy đủ về yêu cầu
-   Thông tin student và staff
-   Ảnh minh chứng từ student và staff
-   Ghi chú từ staff
-   Lịch sử cập nhật

### 3. Duyệt yêu cầu

**Điều kiện:** Yêu cầu phải ở trạng thái **"waiting_admin"**

**Bước 1:** Vào chi tiết yêu cầu đang chờ duyệt

**Bước 2:** Xem xét thông tin:

-   Kiểm tra ảnh minh chứng từ staff
-   Đọc ghi chú từ staff
-   Xác nhận thông tin yêu cầu

**Bước 3:** Chọn một trong hai hành động:

#### A. Duyệt yêu cầu (Approve)

1. Điền **Ghi chú** (tùy chọn)
2. Click nút **"Duyệt yêu cầu"** (màu xanh)

**Kết quả:**

-   Trạng thái chuyển sang "admin_approved"
-   Student và Staff nhận thông báo
-   Nhiệm vụ hoàn thành

#### B. Từ chối yêu cầu (Reject)

1. Điền **Lý do từ chối** ⭐ (bắt buộc)
2. Click nút **"Từ chối yêu cầu"** (màu đỏ)
3. Xác nhận trong popup

**Kết quả:**

-   Trạng thái chuyển sang "admin_rejected"
-   Staff nhận thông báo và có thể cập nhật lại
-   Student cũng nhận thông báo

---

## 🔧 Tính năng tự động

### Auto-assign Staff

Khi student gửi yêu cầu:

1. Hệ thống tự động tìm staff có ít nhiệm vụ nhất
2. Nhiệm vụ được tính: Số requests có status != "admin_approved"
3. Gán staff và chuyển trạng thái sang "assigned"
4. Nếu không có staff nào, yêu cầu giữ nguyên "pending"

### State Machine Validation

Hệ thống tự động kiểm tra và chỉ cho phép chuyển trạng thái hợp lệ:

-   `pending` → `assigned` (tự động)
-   `assigned` → `staff_done` → `waiting_admin` (tự động)
-   `waiting_admin` → `admin_approved` / `admin_rejected` (admin)
-   `admin_rejected` → `staff_done` → `waiting_admin` (staff cập nhật lại)

---

## 📱 Routes và URLs

### Student Routes

```
GET  /student/trash-requests              → Danh sách
GET  /student/trash-requests/create      → Tạo mới
POST /student/trash-requests             → Lưu yêu cầu
GET  /student/trash-requests/{id}        → Chi tiết
```

### Staff Routes

```
GET  /staff/trash-requests               → Danh sách nhiệm vụ
GET  /staff/trash-requests/{id}          → Chi tiết
GET  /staff/trash-requests/{id}/edit     → Form cập nhật
PUT  /staff/trash-requests/{id}          → Lưu cập nhật
```

### Admin Routes

```
GET  /admin/trash-requests               → Danh sách tất cả
GET  /admin/trash-requests/{id}          → Chi tiết
POST /admin/trash-requests/{id}/approve  → Duyệt
POST /admin/trash-requests/{id}/reject   → Từ chối
```

---

## ⚠️ Lưu ý quan trọng

1. **Upload ảnh:**

    - Chỉ chấp nhận: JPG, JPEG, PNG, WEBP
    - Kích thước tối đa: 5MB
    - Ảnh được lưu trong `storage/app/public/trash-requests/`

2. **Quyền truy cập:**

    - Student chỉ xem được yêu cầu của mình
    - Staff chỉ xem được nhiệm vụ được gán
    - Admin xem được tất cả

3. **Trạng thái:**

    - Không thể chỉnh sửa yêu cầu sau khi đã gửi
    - Staff chỉ cập nhật được khi status là "assigned" hoặc "admin_rejected"
    - Admin chỉ duyệt được khi status là "waiting_admin"

4. **Auto-assign:**
    - Ưu tiên staff có ít nhiệm vụ nhất
    - Admin cũng có thể được gán nếu cần

---

## 🐛 Xử lý lỗi thường gặp

### Lỗi: "Không thể chuyển trạng thái"

-   **Nguyên nhân:** Cố gắng chuyển trạng thái không hợp lệ
-   **Giải pháp:** Kiểm tra lại luồng state machine

### Lỗi: "Bạn không có quyền truy cập"

-   **Nguyên nhân:** Truy cập yêu cầu không thuộc về bạn
-   **Giải pháp:** Chỉ xem yêu cầu/nhiệm vụ của mình

### Lỗi: "Chỉ có thể duyệt yêu cầu đang chờ duyệt"

-   **Nguyên nhân:** Cố gắng duyệt yêu cầu không ở trạng thái "waiting_admin"
-   **Giải pháp:** Kiểm tra lại trạng thái yêu cầu

### Lỗi upload ảnh

-   **Nguyên nhân:** File quá lớn hoặc sai định dạng
-   **Giải pháp:** Kiểm tra kích thước và định dạng file

---

## 📊 Dashboard và Thống kê

### Admin Dashboard

-   Tổng số yêu cầu
-   Số yêu cầu đang chờ duyệt
-   Số yêu cầu đã duyệt
-   Số yêu cầu bị từ chối

### Staff Dashboard

-   Số nhiệm vụ đang có
-   Số nhiệm vụ đã hoàn thành
-   Số nhiệm vụ đang chờ duyệt

---

## 🎉 Kết luận

Hệ thống yêu cầu thu gom rác hoạt động hoàn toàn tự động từ khi student gửi yêu cầu đến khi admin duyệt. Mỗi vai trò có quyền và trách nhiệm rõ ràng, đảm bảo quy trình minh bạch và hiệu quả.

**Chúc bạn sử dụng thành công!** 🚀
