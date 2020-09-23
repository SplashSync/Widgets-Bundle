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
echo "** Restart Docker ..."
echo "*************************************************************************"

docker-compose stop
docker-compose up -d

echo "*************************************************************************"
echo "** Configure the Demo ..."
echo "*************************************************************************"

docker-compose exec symfony php bin/console doctrine:schema:update --force
docker-compose exec symfony php bin/console cache:clear
