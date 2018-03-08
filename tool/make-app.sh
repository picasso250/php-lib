#!/bin/bash

set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $0 name"
  exit
fi

mkdir $1 -v
cp -r $(pwd)/$(dirname $0)/../app-tpl/* $1/ -v
