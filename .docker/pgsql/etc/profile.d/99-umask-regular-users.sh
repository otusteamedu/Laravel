#!/bin/sh

if [[ `id -u` -ge 1000 || `id -un` = "docker" ]]; then
    umask 0002
fi
