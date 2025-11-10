# Script để tự động rebase và sửa commit messages từ Bootstrap sang Tailwind
# Sử dụng git rebase -i với editor tự động

Write-Host "=== Refactor Bootstrap to Tailwind Commit Messages ==="
Write-Host ""

# Tìm base commit (commit trước commit đầu tiên có Bootstrap)
$baseCommit = "289a214"

Write-Host "Base commit: $baseCommit"
Write-Host ""
Write-Host "Commits sẽ được sửa:"
git log --oneline --author="ThanhTamSW" --grep="Bootstrap" -i --format="  %h - %s"
Write-Host ""

# Tạo file rebase script
$rebaseScript = @"
#!/bin/sh
# Auto-edit commit messages
exec < /dev/tty
while true; do
    read -r line
    if echo "$line" | grep -q "Bootstrap\|bootstrap"; then
        echo "$line" | sed 's/^pick/reword/'
    else
        echo "$line"
    fi
done
"@

Write-Host "Để thực hiện rebase, chạy:"
Write-Host "  git rebase -i $baseCommit"
Write-Host ""
Write-Host "Trong editor, đổi 'pick' thành 'reword' cho các commit có 'Bootstrap'"
Write-Host "Sau đó sửa commit messages từ 'Bootstrap' thành 'Tailwind'"

