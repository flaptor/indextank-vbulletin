#!/bin/bash

echo "Enter your api url:"
read API_URL

# copy files to a set up dir, just to avoid touching the original ones
cp indextank_plugin.php.new indextank_plugin.php

sed "s|<API_URL>|$API_URL|" indextank_plugin.php > indextank_plugin.php.tmp
mv -f indextank_plugin.php.tmp indextank_plugin.php

echo 'Setup successful'

