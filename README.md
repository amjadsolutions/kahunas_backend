Project Setup Instructions

Follow these simple steps to set up and run the project:

1. Run Docker Containers

Make sure Docker is installed and running on your system.

Navigate to the project directory in your terminal.

Run the following command to build and start the containers:

docker-compose up --build

This command will start all the services defined in the docker-compose.yml file.

2. Set Up Local Database (Optional)

    Update the .env file to point to your local database credentials.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=kahunas_db
    DB_USERNAME=root
    DB_PASSWORD=your_password

3. Access the Application

Laravel App: http://localhost:8000

Nginx Server: http://localhost:8001