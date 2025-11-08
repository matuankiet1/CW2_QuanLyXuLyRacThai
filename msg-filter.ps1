# Message filter script for git filter-branch
$input = [Console]::In.ReadToEnd()
if ($input) {
    $output = $input -replace 'Bootstrap', 'Tailwind' -replace 'bootstrap', 'tailwind'
    [Console]::Out.Write($output)
}

