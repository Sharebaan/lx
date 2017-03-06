echo -e "\e[42mTravelian Install\e[0m\n"

echo -e "\e[32mCreating configuration file .env\e[0m"

read -p "Database USER: " DBUSER
read -s -p "Database PASSWORD: " DBPASS
echo -e ""
read -p "Database NAME: " DBNAME

touch .env | echo "APP_ENV=local
APP_DEBUG=true
APP_KEY=nJXc5GqmYX5WfeMxigE5dciyW37wYlnL

DB_HOST=localhost
DB_DATABASE=$DBNAME
DB_USERNAME=$DBUSER
DB_PASSWORD=$DBPASS


CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_DRIVER=sync

MAIL_DRIVER=mail
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null" > .env
echo -e "\e[32mConfiguration file created succesfully\e[0m\n"

echo -e "\e[32mInstalling laravel and packages\e[0m"
php composer.phar install
echo -e "\e[32mLaravel and packages instalation completed succesfully\e[0m\n"

echo -e "\e[32mMigrating and seeding the database\e[0m"
php artisan migrate
php artisan db:seed
echo -e "\e[32mMigraton and seeding completed succesfully\e[0m\n"
mkdir storage/framework/views
chmod -R 777 .
echo -e "\e[42mInstallation completed succesfully\e[0m\n"
