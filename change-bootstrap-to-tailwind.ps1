# Script để thay đổi commit messages từ Bootstrap sang Tailwind
# Sử dụng git rebase -i với editor script

Write-Host "Finding commits with 'Bootstrap' in message..."
$commits = git log --all --format="%H|%s" --grep="Bootstrap" -i

foreach ($line in $commits) {
    $parts = $line -split '\|'
    $hash = $parts[0]
    $oldMsg = $parts[1]
    $newMsg = $oldMsg -replace 'Bootstrap', 'Tailwind' -replace 'bootstrap', 'tailwind'
    
    Write-Host "`nCommit: $hash"
    Write-Host "  Old: $oldMsg"
    Write-Host "  New: $newMsg"
    
    # Sử dụng git filter-branch cho từng commit
    # Hoặc sử dụng git rebase -i
}

Write-Host "`nTo change these commit messages, use:"
Write-Host "  git rebase -i <base-commit>"
Write-Host "Then change 'pick' to 'reword' for each commit and update the message"

# Hoặc sử dụng git filter-branch với inline script
Write-Host "`nAttempting to use git filter-branch..."
$env:FILTER_BRANCH_SQUELCH_WARNING = "1"

# Tạo inline filter script
$filterCmd = '$input = [Console]::In.ReadLine(); while ($input) { $input = $input -replace "Bootstrap", "Tailwind" -replace "bootstrap", "tailwind"; [Console]::Out.WriteLine($input); $input = [Console]::In.ReadLine() }'

git filter-branch -f --msg-filter "powershell -Command $filterCmd" -- --all 2>&1 | Select-Object -Last 20

