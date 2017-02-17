#!/bin/sh
unset $(git rev-parse --local-env-vars)
cd /home/wwwroot/default/tpproject
/usr/bin/git pull 

