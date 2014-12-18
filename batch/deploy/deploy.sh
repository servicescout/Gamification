#!/bin/bash

DIR=$(cd "$(dirname "$0")" && pwd)
ROOTDIR=$(cd $DIR && cd ../.. && pwd)
DESTDIR=/var/www/game

rsync -rltDzCE --progress --no-p --no-g --chmod=ug=rwX,Dg+s,o-rwx --force --delete --exclude-from=$ROOTDIR/config/rsync_exclude.txt $ROOTDIR/ $DESTDIR
