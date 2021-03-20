#!/bin/bash
echo ""
echo "-----------------------------------------------------------------------------------------------------------------------"
echo ""
echo -e "\e[43m\e[30m                                                        PHPSTAN:                                                        \033[0m"
echo ""

./vendor/bin/phpstan analyse src config papi --debug phpstan.neon

echo "-----------------------------------------------------------------------------------------------------------------------"
echo ""
echo -e "\e[43m\e[30m                                                        PHPCBF:                                                        \033[0m"
echo ""
./vendor/bin/phpcbf src config papi

echo "-----------------------------------------------------------------------------------------------------------------------"
echo ""
echo -e "\e[43m\e[30m                                                        PHPCS:                                                        \033[0m"
echo ""
./vendor/bin/phpcs src config papi