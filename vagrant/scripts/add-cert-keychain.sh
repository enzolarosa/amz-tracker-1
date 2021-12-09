#!/usr/bin/env bash

cp ../provision/ssl/star.amz.local.crt /usr/share/ca-certificates/star.amz.local.crt
echo "star.amz.local.crt" >> /etc/ca-certificates.conf
sudo update-ca-certificates --fresh
