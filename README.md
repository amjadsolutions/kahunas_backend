Project Setup Instructions

Follow these simple steps to set up and run the project:

1. Run Docker Containers

Make sure Docker is installed and running on your system.

Navigate to the project directory in your terminal.

Run the following command to build and start the containers:

docker-compose up --build

This command will start all the services defined in the docker-compose.yml file.

2. Set Up Local Database

    Update the .env file to point to your local database credentials.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=kahunas_db
    DB_USERNAME=root
    DB_PASSWORD=your_password



    if you want want to run mysql container just uncomment the code inside the docker-compose.yml, and create the database with the name kahunas_db,
    & then run the following command

    docker exec -it kahunas-container  bash 
    php artisan migrate



    Note: Make Sure you Mysql container is running



3. Access the Application

Laravel App: http://localhost:8000

Nginx Server: http://localhost:8001