#!/bin/bash

echo "Enter your api url:"
read API_URL

echo "Enter your index name:"
read INDEX_NAME

# set up php files
if [ ! -e "indextank" ]
then
    mkdir indextank
fi

# copy files to a set up dir, just to avoid touching the original ones
cp indextank_nosetup/indextank_plugin.php indextank/indextank_plugin.php
cp indextank_nosetup/indextank_client.php indextank/indextank_client.php

cd indextank

sed "s|<API_URL>|$API_URL|" indextank_plugin.php > indextank_plugin.php.1
sed "s|<INDEX_NAME>|$INDEX_NAME|" indextank_plugin.php.1 > indextank_plugin.php
rm indextank_plugin.php.1

cd ..

echo 'Setup successful'

