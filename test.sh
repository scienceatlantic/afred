#!/bin/bash

# Clear the apache afred folder
rm -rf /var/www/html/afred/*

# Copy the files over
cp -r ~/ws/test-afred/dist/* /var/www/html/afred/
