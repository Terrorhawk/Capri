#!/bin/sh

echo -n "Enter the PHP binary path or press enter to use default [/usr/local/bin/php]: "
read PHPB

if [ "$PHPB" = "" ]; then
  PHPB="/usr/local/bin/php"
fi

echo
echo

echo "PHP DOCUMENT_ROOT variable can be:"
echo  1 - "\$_ENV['DOCUMENT_ROOT']"
echo  2 - "\$_SERVER['DOCUMENT_ROOT']"
echo  3 - "getenv("DOCUMENT_ROOT")"
echo -n "Enter the DOCUMENT_ROOT variable or press enter to use default (1,2,3) [1]: "
read DRP

DRP="\$_ENV['DOCUMENT_ROOT']"

if [ "$DRP" = "3" ]; then
  PHPB="getenv('DOCUMENT_ROOT')"
fi
if [ "$DRP" = "2" ]; then
  PHPB="\$_SERVER['DOCUMENT_ROOT']"
fi

touch files_custom.conf
echo "HTM_CONFIG=inc/config_custom.html" >> files_custom.conf
echo "HTM_CONFIG_O=inc/config.html" >> files_custom.conf
echo "|HTM_CONFIG_O|" > ./inc/config_custom.html
echo "|?SK_PHP=$PHPB|" >> ./inc/config_custom.html
echo "|?SK_ROOT=$DRP.\"/\"|" >> ./inc/config_custom.html

chmod 755 ./inc/config_custom.html
chmod 755 files_custom.conf
chown diradmin:diradmin ./inc/config_custom.html
chown diradmin:diradmin files_custom.conf

exit 0;
