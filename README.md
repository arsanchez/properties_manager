# Properties manager configurations steps

**The following is a guide to configure and run the properties manager application**

1.  Checkout or download this repo from https://github.com/arsanchez/properties_manager

2.  'cd' into the project folder

3.  Install composer dependencies by running 'composer install'

4.  Install and compile the assets using Mix by running 'npm install && npm run dev'

5. Create a new database in your local server and run the script located in the 'db_scripts' folder

   1.  The default database name will be 'property_manager' you can change this is needed 

6. Rename the '.env.example' file to '.env' and provide  your database connection details 

    `DB_CONNECTION=mysql`
    `DB_HOST=[your_host]`
    `DB_PORT=3306`
    `DB_DATABASE=[your_db]`
    `DB_USERNAME=[your_username]`
    `DB_PASSWORD=[your_pass]`

**Running the application**

1. 'cd' into the application forlder and run the command `php artisan:serve`

**Syncing the properties from the api**

1. 'cd' into the application forlder and run the command `php artisan properties:sync`
2. This will start syncing the local data with the API
