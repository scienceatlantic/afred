# Author: 	Prasad Rajandran
# Date: 	July 27, 2016

# Runs Grunt for the "local" branch.
grunt-local:
	grunt --force

# Runs Grunt for the "dev" branch.
grunt-dev: 
	mv ./app/scripts/env.js ./app/scripts/env.local.js
	mv ./app/scripts/env.dev.js ./app/scripts/env.js

	mv ./app/index.html ./app/index.local.html
	mv ./app/index.dev.html ./app/index.html

	grunt --force

	mv ./app/scripts/env.js ./app/scripts/env.dev.js
	mv ./app/scripts/env.local.js ./app/scripts/env.js

	mv ./app/index.html ./app/index.dev.html
	mv ./app/index.local.html ./app/index.html	

	rm ./dist/index.local.html
	rm ./dist/robots.txt

# Runs Grunt for the "production" branch.
grunt-prod:
	mv ./app/scripts/env.js ./app/scripts/env.local.js
	mv ./app/scripts/env.production.js ./app/scripts/env.js

	grunt --force

	mv ./app/scripts/env.js ./app/scripts/env.production.js
	mv ./app/scripts/env.local.js ./app/scripts/env.js

	rm ./dist/index.dev.html
	rm ./dist/robots.txt
	rm ./dist/.htaccess

# Runs tests.
grunt-test:
	mv ./app/scripts/env.js ./app/scripts/env.local.js
	mv ./app/scripts/env.testing.js ./app/scripts/env.js

	grunt test

	mv ./app/scripts/env.js ./app/scripts/env.testing.js
	mv ./app/scripts/env.local.js ./app/scripts/env.js
