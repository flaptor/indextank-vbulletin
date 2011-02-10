#!/bin/bash

echo "Enter your api url:"
read API_URL

# copy files to a set up dir, just to avoid touching the original ones
cp indextank_plugin.php.new indextank_plugin.php

sed "s|<API_URL>|$API_URL|" indextank_plugin.php > indextank_plugin.php.tmp
sed "s|<INDEX_NAME>|$INDEX_NAME|" indextank_plugin.php.tmp > indextank_plugin.php
rm indextank_plugin.php.tmp

echo 'Setup successful'

