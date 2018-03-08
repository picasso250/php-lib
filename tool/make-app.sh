#!/bin/bash

set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $0 name"
  exit
fi

mkdir $1 -v
self_root=$(pwd)/$(dirname $0)/..
cp -r $self_root/app-tpl/* $1/ -v
cp -r $self_root/lib $1/lib -v
