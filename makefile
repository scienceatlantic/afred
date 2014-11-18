DIR_AFRED_ROOT 		= /var/www/html/afred
DIR_APP			= ~/ws/afred/app
DIR_API			= ~/ws/afred/api

deploy: clear-root
	cd app && grunt && cd ..
	sudo cp -r $(DIR_APP)/dist $(DIR_AFRED_ROOT)
	sudo cp -r $(DIR_API) $(DIR_AFRED_ROOT)/api
	sudo chmod -R 757 $(DIR_AFRED_ROOT)/api/app/storage
	
deploy-api: clear-root
	sudo cp -r $(DIR_API) $(DIR_AFRED_ROOT)/api
	sudo chmod -R 757 $(DIR_AFRED_ROOT)/api/app/storage

clear-root:
	sudo rm -rf $(DIR_AFRED_ROOT)
	sudo mkdir $(DIR_AFRED_ROOT)