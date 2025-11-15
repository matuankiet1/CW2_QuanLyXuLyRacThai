#!/bin/bash
# Script để thay đổi commit messages

git filter-branch -f --msg-filter 'perl -pe "s/Bootstrap/Tailwind/g; s/bootstrap/tailwind/g"' -- --all

