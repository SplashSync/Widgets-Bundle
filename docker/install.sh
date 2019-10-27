################################################################################
#
#  This file is part of SplashSync Project.
# 
#  Copyright (C) Splash Sync <www.splashsync.com>
# 
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
# 
#  For the full copyright and license information, please view the LICENSE
#  file that was distributed with this source code.
# 
#  @author Bernard Paquier <contact@splashsync.com>
#
################################################################################

echo "*************************************************************************"
echo "** Build Widgets Docker ..."
echo "*************************************************************************"

docker-compose up -d --build

echo "*************************************************************************"
echo "** Configure Symfony ..."
echo "*************************************************************************"

docker-compose exec symfony composer install
docker-compose exec symfony php /app/bin/console doctrine:schema:update --force
docker-compose exec symfony php /app/bin/console cache:clear

echo "*************************************************************************"
echo "** Install Webpack "
echo "*************************************************************************"
docker-compose run --rm node yarn encore dev