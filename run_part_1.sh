#!/bin/zsh

php PART1_DONE.php | sort -t\| > fl2 && gource -c 3 -s 2 fl2 --hide usernames,filenames,bloom,dirnames --title "LinkTrack"
