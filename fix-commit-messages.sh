#!/bin/bash

# Script để thay đổi commit messages từ Bootstrap sang Tailwind
git filter-branch --msg-filter '
    sed "s/Bootstrap/Tailwind/g; s/bootstrap/tailwind/g"
' -- --all

