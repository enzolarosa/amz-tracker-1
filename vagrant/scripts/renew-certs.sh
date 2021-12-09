#!/usr/bin/env bash

ssh-keygen -f star.amz.local.key -t rsa -N ''
openssl req \
    -newkey rsa:2048 \
    -x509 \
    -nodes \
    -keyout star.amz.local.key \
    -new \
    -out star.amz.local.crt \
    -config amz-openssl.cnf \
    -sha256 \
    -days 365\
    -extensions v3_req

openssl rsa -in star.amz.local.key -out star.amz.local.key

mv star.amz.local.crt ../provision/nginx/ssl/star.amz.local.crt
mv star.amz.local.key ../provision/nginx/ssl/star.amz.local.key
mv star.amz.local.key.pub ../provision/nginx/ssl/star.amz.local.key.pub
