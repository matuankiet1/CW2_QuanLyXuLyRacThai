# Hướng dẫn thay đổi commit messages từ Bootstrap sang Tailwind

## Tóm tắt
Đã tạo branch `refactor/bootstrap-to-tailwind` và tìm thấy **5 commits** của ThanhTamSW cần sửa:

1. `c544b6b` - merge(fix_admin_ui_suite): unify Dashboard, Posts, Events, Roles to Bootstrap admin UI
2. `f278562` - merge(fix_ui_admin): add admin UI assets and migrate users page to Bootstrap
3. `befdf1d` - feat(admin/users): move users index to admin-with-sidebar and rework UI with Bootstrap table, badges, actions
4. `3352372` - feat(admin ui): unify Dashboard, Posts, Events, Roles to admin-with-sidebar + Bootstrap UI
5. `0b61727` - feat(admin/users): move users index to admin-with-sidebar and rework UI with Bootstrap table, badges, actions

## Cách thực hiện

### Cách 1: Sử dụng git rebase -i (Recommended)

1. Đảm bảo đang ở branch `refactor/bootstrap-to-tailwind`:
```bash
git checkout refactor/bootstrap-to-tailwind
```

2. Bắt đầu interactive rebase từ commit trước các commit cần sửa:
```bash
git rebase -i 289a214
```

3. Trong editor, tìm các dòng có commit messages chứa "Bootstrap" và đổi `pick` thành `reword` (hoặc `r`)

4. Khi editor mở lại cho mỗi commit, sửa commit message:
   - Thay `Bootstrap` thành `Tailwind`
   - Thay `bootstrap` thành `tailwind`

5. Lưu và đóng editor sau mỗi lần sửa

6. Sau khi hoàn tất rebase, force push (cẩn thận!):
```bash
git push origin refactor/bootstrap-to-tailwind --force
```

### Cách 2: Sử dụng git filter-branch (Automated - có thể gặp vấn đề trên Windows)

```bash
git filter-branch -f --msg-filter "perl -pe 's/Bootstrap/Tailwind/g; s/bootstrap/tailwind/g'" -- --all
```

### Cách 3: Tạo commits mới (Không thay đổi history)

Tạo các commit mới với messages đã sửa và merge vào main (không được khuyến nghị vì sẽ tạo duplicate commits).

## Lưu ý

⚠️ **CẢNH BÁO**: Thay đổi commit history sẽ rewrite git history. Nếu đã push lên remote và có người khác đang làm việc trên cùng branch, cần phải:
- Thông báo với team
- Sử dụng `--force` khi push (cẩn thận!)
- Cân nhắc tạo branch mới thay vì rewrite history

## Commit messages mới (sau khi sửa)

1. `merge(fix_admin_ui_suite): unify Dashboard, Posts, Events, Roles to Tailwind admin UI`
2. `merge(fix_ui_admin): add admin UI assets and migrate users page to Tailwind`
3. `feat(admin/users): move users index to admin-with-sidebar and rework UI with Tailwind table, badges, actions`
4. `feat(admin ui): unify Dashboard, Posts, Events, Roles to admin-with-sidebar + Tailwind UI (tables, forms, cards, modal)`
5. `feat(admin/users): move users index to admin-with-sidebar and rework UI with Tailwind table, badges, actions`

