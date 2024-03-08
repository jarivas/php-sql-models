#!/usr/bin/bash
FILE=.git/hooks/pre-commit

if [ ! -f "$FILE" ]; then
    cp pre-commit-hook "$FILE"
fi