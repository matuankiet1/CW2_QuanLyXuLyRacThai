# Script PowerShell để thay đổi commit messages từ Bootstrap sang Tailwind

# Lấy danh sách các commit có "Bootstrap" trong message
$commits = git log --all --format="%H|%s" --grep="Bootstrap" -i

foreach ($line in $commits) {
    $parts = $line -split '\|'
    $commitHash = $parts[0]
    $oldMessage = $parts[1]
    $newMessage = $oldMessage -replace 'Bootstrap', 'Tailwind' -replace 'bootstrap', 'tailwind'
    
    Write-Host "Changing commit $commitHash"
    Write-Host "  Old: $oldMessage"
    Write-Host "  New: $newMessage"
    
    # Sử dụng git commit --amend không hoạt động cho các commit cũ
    # Cần sử dụng git filter-branch hoặc git rebase
}

# Sử dụng git filter-branch với PowerShell
$env:FILTER_BRANCH_SQUELCH_WARNING = "1"
git filter-branch -f --msg-filter "powershell -Command `"`$input = `$input -replace 'Bootstrap', 'Tailwind' -replace 'bootstrap', 'tailwind'; Write-Output `$input`"" -- --all

