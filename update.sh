echo -e "\e[42mTravelian Update\e[0m\n"
read -p "Are you sure you want to update Travelian?(y/n) " yn
if [ $yn = "y" ]; then
	echo -e "\e[32mUpdating Travelian...\e[0m"
	git pull origin master:master
	php composer.phar update
	php artisan migrate
	rm -rf storage/framework/views/*
	mkdir storage/framework/views
	chmod -R 777 storage/framework/views
	echo -e "\n\e[42mUpdate completed succesfully\e[0m"
fi
