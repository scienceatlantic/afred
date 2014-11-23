DIR_AFRED_ROOT 	= /var/www/html/afred
DIR_APP		= ~/ws/afred/app
DIR_API		= ~/ws/afred/api

deploy: deploy-api deploy-app
	
deploy-api:
	sudo rm -rf $(DIR_AFRED_ROOT)/api
	sudo cp -r $(DIR_API) $(DIR_AFRED_ROOT)/api
	sudo chmod -R 757 $(DIR_AFRED_ROOT)/api/app/storage
	
deploy-app:
	cd app && grunt && cd ..
	sudo rm -rf $(DIR_AFRED_ROOT)/fonts
	sudo rm -rf $(DIR_AFRED_ROOT)/images
	sudo rm -rf $(DIR_AFRED_ROOT)/scripts
	sudo rm -rf $(DIR_AFRED_ROOT)/styles
	sudo rm -rf $(DIR_AFRED_ROOT)/views
	sudo rm -rf $(DIR_AFRED_ROOT)/*.*
	sudo cp -r $(DIR_APP)/dist/* $(DIR_AFRED_ROOT)

clear-root:
	sudo rm -rf $(DIR_AFRED_ROOT)
	sudo mkdir $(DIR_AFRED_ROOT)