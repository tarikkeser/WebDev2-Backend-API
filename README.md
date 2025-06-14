## About
The Dog Walker Web Application is a platform designed to facilitate interaction between dog owners and dog walkers. It allows owners to manage their pets, schedule appointments, and manage their profiles, while walkers can accept appointment requests and manage their own profiles. The system will provide both Owner and Walker roles, each with its own set of features and functionalities.

## Built With
This part of the project focuses on the backend implementation using RESTAPI practices. It is designed to be run in a Docker environment. The project follows the Model-View-Controller (MVC) architecture. For the database, it uses MySQL (MariaDB) and includes PHPMyAdmin for database management.


## Getting Started
Run the SQL query in the `sql` folder to create the database and tables in PHPMyAdmin. The SQL file is named `developmentdb.sql`.

## Usage

- Start local

In a terminal, from the cloned/forked/download project folder, run:

```bash
docker compose up
```

NGINX will now serve files in the app/public folder. Visit localhost in your browser to check.
PHPMyAdmin is accessible on localhost:8080

If you want to stop the containers, press Ctrl+C.

Or run:

```bash
docker compose down
```

