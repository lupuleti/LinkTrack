#!/bin/zsh

./ubigraph/bin/ubigraph_server &
sleep 1
python PART2_DONE.py
