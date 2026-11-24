#!/bin/sh
set -eu
php /usr/local/bin/railway-init.php
exec apache2-foreground
