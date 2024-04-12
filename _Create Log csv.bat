@echo off
title create commit log
git log --pretty=format:%h,%an,%ae,%s > log.csv
git log --all --pretty="%x40%h%x2C%an%x2C%ad%x2C%x22%s%x22%x2C" --shortstat > logDetailed.csv
pause
