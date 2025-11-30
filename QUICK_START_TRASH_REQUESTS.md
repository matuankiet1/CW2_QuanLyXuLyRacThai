# ⚡ Quick Start - Yêu cầu thu gom rác

## 🚀 Bắt đầu nhanh trong 5 phút

### Bước 1: Chạy Migrations (nếu chưa chạy)
```bash
php artisan migrate
```

### Bước 2: Tạo tài khoản test (nếu cần)

#### Tạo Student:
- Đăng ký tài khoản mới → Role: Student
- Hoặc dùng tài khoản student có sẵn

#### Tạo Staff:
- Admin vào: `/admin/roles`
- Tạo user mới hoặc đổi role user thành "staff"

#### Admin:
- Đã có sẵn: `admin@ecowaste.com`

---

## 📝 Quy trình test nhanh

### 1️⃣ Student gửi yêu cầu (2 phút)

1. Đăng nhập với tài khoản **Student**
2. Vào: `http://localhost/student/trash-requests/create`
3. Điền form:
   - Địa điểm: "Khu A, Tòa nhà B"
   - Loại rác: "Rác tái chế"
   - Mô tả: "Có khoảng 5kg rác tái chế cần thu gom"
   - Upload ảnh (tùy chọn)
4. Click **"Gửi yêu cầu"**
5. ✅ Hệ thống tự động gán staff

### 2️⃣ Staff xử lý nhiệm vụ (2 phút)

1. Đăng nhập với tài khoản **Staff** (đã được gán)
2. Vào: `http://localhost/staff/trash-requests`
3. Xem nhiệm vụ mới được gán
4. Click **"Xem chi tiết"** → **"Cập nhật"**
5. Upload ảnh minh chứng sau khi thu gom
6. Ghi chú: "Đã thu gom thành công, 5kg rác tái chế"
7. Click **"Hoàn thành nhiệm vụ"**
8. ✅ Tự động chuyển sang "Chờ duyệt"

### 3️⃣ Admin duyệt (1 phút)

1. Đăng nhập với tài khoản **Admin**
2. Vào: `http://localhost/admin/trash-requests`
3. Xem yêu cầu đang chờ duyệt (màu cam)
4. Click **"Xem"** → Xem xét thông tin
5. Click **"Duyệt yêu cầu"** hoặc **"Từ chối"** (nếu cần)
6. ✅ Hoàn thành!

---

## 🎯 Các trường hợp test

### Test Case 1: Luồng thành công
```
Student tạo → Auto-assign → Staff cập nhật → Admin duyệt ✅
```

### Test Case 2: Admin từ chối
```
Student tạo → Auto-assign → Staff cập nhật → Admin từ chối ❌
→ Staff cập nhật lại → Admin duyệt ✅
```

### Test Case 3: Không có staff
```
Student tạo → Không có staff → Giữ nguyên "pending"
→ Tạo staff → Hệ thống tự động gán
```

### Test Case 4: Nhiều staff
```
Tạo 3 staff
Student 1 tạo yêu cầu → Gán cho staff ít nhiệm vụ nhất
Student 2 tạo yêu cầu → Gán cho staff ít nhiệm vụ nhất
→ Hệ thống tự động cân bằng workload
```

---

## 🔍 Kiểm tra nhanh

### Checklist Student:
- [ ] Có thể tạo yêu cầu mới
- [ ] Xem được danh sách yêu cầu của mình
- [ ] Xem được thông tin staff được gán
- [ ] Xem được trạng thái cập nhật

### Checklist Staff:
- [ ] Xem được nhiệm vụ được gán
- [ ] Có thể cập nhật nhiệm vụ (assigned/rejected)
- [ ] Upload được ảnh minh chứng
- [ ] Xem được phản hồi từ admin

### Checklist Admin:
- [ ] Xem được tất cả yêu cầu
- [ ] Tìm kiếm và lọc được
- [ ] Có thể duyệt yêu cầu (waiting_admin)
- [ ] Có thể từ chối yêu cầu (có lý do)

---

## 🐛 Debug nhanh

### Yêu cầu không được gán staff?
```bash
# Kiểm tra có staff nào không
php artisan tinker
>>> User::whereIn('role', ['staff', 'admin'])->count();
```

### Không thể chuyển trạng thái?
- Kiểm tra: `app/Services/TrashRequestStateMachine.php`
- Xem log: `storage/logs/laravel.log`

### Ảnh không hiển thị?
```bash
# Tạo symbolic link
php artisan storage:link
```

---

## 📞 Cần hỗ trợ?

Xem file chi tiết: `HUONG_DAN_SU_DUNG_YEU_CAU_THU_GOM_RAC.md`

