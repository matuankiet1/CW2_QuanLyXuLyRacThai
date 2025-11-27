# Script để thay đổi commit messages từ Bootstrap sang Tailwind
$env:FILTER_BRANCH_SQUELCH_WARNING = "1"

# Tạo script filter tạm
$filterScript = @"
`$input = [Console]::In.ReadLine()
while (`$input -ne `$null) {
    `$input = `$input -replace 'Bootstrap', 'Tailwind' -replace 'bootstrap', 'tailwind'
    [Console]::Out.WriteLine(`$input)
    `$input = [Console]::In.ReadLine()
}
"@

$filterScript | Out-File -FilePath "msg-filter.ps1" -Encoding UTF8

# Sử dụng git filter-branch
git filter-branch -f --msg-filter "powershell -File msg-filter.ps1" -- --all

# Xóa file tạm
Remove-Item "msg-filter.ps1" -ErrorAction SilentlyContinue

Write-Host "Done! Commit messages have been updated from Bootstrap to Tailwind."

