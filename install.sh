#!/bin/bash
apacheUser=$(ps -ef | egrep '(httpd|apache2|apache)' | grep -v `whoami` | grep -v root | head -n1 | awk '{print $1}')
echo "Current user is $apacheUser. Fixing permissions and creating folders"

# making actions folder
if [ ! -d "db/actions" ]; then
  mkdir db/actions
else
  echo "actions folder already exists. skipping"
fi
# making usercreds folder
if [ ! -d "db/usercreds" ]; then
  mkdir db/usercreds
else
  echo "usercreds folder already exists. skipping"
fi
# making botvacs folder
if [ ! -d "db/botvacs" ]; then
  mkdir db/botvacs
else
  echo "botvacs folder already exists. skipping"
fi
# making lidar folder
if [ ! -d "db/lidar" ]; then
  mkdir db/lidar
else
  echo "lidar folder already exists. skipping"
fi
# making schedules folder
if [ ! -d "db/schedules" ]; then
  mkdir db/schedules
else
  echo "schedules folder already exists. skipping"
fi

# changing permissions
echo "Fixing permissions of the environment to the correct apache user"
chown -R $apacheUser:$apacheUser *
