#!/bin/bash

DIR=$(cd "$(dirname "$0")" && pwd)
ROOTDIR=$(cd $DIR && cd ../.. && pwd)
TMPDIR=/var/tmp/game

rsync -rltDzC --progress --force --delete --exclude-from=$ROOTDIR/config/rsync_exclude.txt $ROOTDIR/ tflanders@$1:$TMPDIR
