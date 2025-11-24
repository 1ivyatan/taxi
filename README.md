# taxi

Taxi service (made as a part of assignment).


## Functionalities:

* Order a ride on a specific date
* Maintain list of cars used in rides
* Authentificated user roles (drivers organize rides, administration maintain list of cars and users)

# Setup

* Install PHP, MySQL and a hosting software or a development toolkit (Apache, Herd, Laragon, XAMPP...)
* Move site files to respective hosting software directory
* Configure database in `db/` Directory
    * `init.sql` Initiate table and root user creation
        * Username, password: `admin`
        * Run this query on the database
    * `credentials.php` Database connection credentials
