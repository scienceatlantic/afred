DIR_DOCUMENT_ROOT 	= /var/www/html/afred
DIR_APP_APP			= ~/ws/afred/app/app
DIR_APP_DIST		= ~/ws/afred/app/dist
DIR_API				= ~/ws/afred/api

deploy:
	sudo rm -rf $(DIR_DOCUMENT_ROOT)
	cd app && grunt && cd ..
	sudo cp -r $(DIR_APP_DIST) $(DIR_DOCUMENT_ROOT)
	sudo cp -r $(DIR_API) $(DIR_DOCUMENT_ROOT)
	sudo chmod -R 757 $(DIR_DOCUMENT_ROOT)/app/storage
