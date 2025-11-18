# Refactor Bootstrap to Tailwind - Commit Messages

## Commits cần thay đổi (của ThanhTamSW):

1. **c544b6b** - `merge(fix_admin_ui_suite): unify Dashboard, Posts, Events, Roles to Bootstrap admin UI`
   → `merge(fix_admin_ui_suite): unify Dashboard, Posts, Events, Roles to Tailwind admin UI`

2. **f278562** - `merge(fix_ui_admin): add admin UI assets and migrate users page to Bootstrap`
   → `merge(fix_ui_admin): add admin UI assets and migrate users page to Tailwind`

3. **befdf1d** - `feat(admin/users): move users index to admin-with-sidebar and rework UI with Bootstrap table, badges, actions`
   → `feat(admin/users): move users index to admin-with-sidebar and rework UI with Tailwind table, badges, actions`

4. **3352372** - `feat(admin ui): unify Dashboard, Posts, Events, Roles to admin-with-sidebar + Bootstrap UI (tables, forms, cards, modal)`
   → `feat(admin ui): unify Dashboard, Posts, Events, Roles to admin-with-sidebar + Tailwind UI (tables, forms, cards, modal)`

5. **0b61727** - `feat(admin/users): move users index to admin-with-sidebar and rework UI with Bootstrap table, badges, actions`
   → `feat(admin/users): move users index to admin-with-sidebar and rework UI with Tailwind table, badges, actions`

## Cách thực hiện:

### Option 1: Sử dụng git rebase -i (Manual)
```bash
git rebase -i 289a214^
# Đổi 'pick' thành 'reword' cho các commit cần sửa
# Sửa commit messages khi editor mở
```

### Option 2: Sử dụng git filter-branch (Automated - đã thử nhưng gặp vấn đề trên Windows)
```bash
git filter-branch -f --msg-filter "sed 's/Bootstrap/Tailwind/g; s/bootstrap/tailwind/g'" -- --all
```

### Option 3: Tạo commits mới với messages đã sửa (Recommended)
Tạo các commit mới với messages đã sửa, sau đó merge vào main.

