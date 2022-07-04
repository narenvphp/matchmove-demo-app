## Deployment instructions

To begin, run the following command to download the Matchmove’s Demo App using Git:

    git clone git@github.com:narenvphp/matchmove-demo-app.git

Then run the below commands to run the app
    
    cd matchmove-demo-app
    composer install
    cp .env.example .env
    php artisan key:generate

Setting Up the Database:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=match_move
    DB_USERNAME=root
    DB_PASSWORD=admin@123
    
Then run below command to refresh the database and create admin user

    php artisan app:refresh

Next to run app 

    php artisan serve

Testing

in order to run unit test cases, run below command

    composer test
    
    
Documentation
    
    php artisan lrd:generate
    
    // to open documentaion
    http://127.0.0.1:8000/request-docs
    
    
 
