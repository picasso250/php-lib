#!/usr/bin/env bash

# assume we are in app root

if [ -d deployment_hooks ] ; then
    chmod +x deployment_hooks/*.sh
    bash -xe deployment_hooks/application_stop.sh
    bash -xe deployment_hooks/before_install.sh
    bash -xe deployment_hooks/application_start.sh
    bash -xe deployment_hooks/validate_service.sh
    bash -xe deployment_hooks/after_install.sh
fi