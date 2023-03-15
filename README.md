# parking

Parking System
This is a parking system application built using Laravel.

Installing
Clone the repository to your local machine:

git clone https://github.com/ajithnsimon/parking.git

Navigate to the project directory:

cd parking

Install the project dependencies:

composer install

Create a MySQL database for the application:

data base name = parking
username = root
password = 

Seed the database with sample data:

php artisan db:seed --class=SlotsTableSeeder

Run the application:

php artisan serve


Access the application in your web browser at http://127.0.0.1:8000/

Api for booking

http://127.0.0.1:8000/api/bookings

The request should include the following parameters:

name:AJITH
phone:8606450291
vehicle_number:KL07CR0007
start_time:2023-03-18T12:00:00Z
end_time:2023-03-20T11:00:00Z
driver_license: binary file

View the list of customers and their parking slot appointments

http://127.0.0.1:8000/
