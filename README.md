
# Term Project - College Event Website

We were tasked to solve a problem of students from various universities not having a central place to find 
events. We had to create a website that let users sign in, join registered student organizations, create events, 
and comment on events. This would allow students to collaborate and interact with each other instead of 
managing these events on their own. With all the events centralized, we could also make sure no events 
overlap, so students didn’t accidentally schedule two events at the same time and place causing certain havoc.


## Run Locally

To run the webserver, simply install Docker Desktop, this is a very popular software and not invasive. This will let you run programs in an isolated container for security and portability. In this project there are three containers, one php:7.4-apache, mysql:latest, and phpmyadmin:latest. Simply run the following commands from the terminal within the project's root directory.

To start the webserver:
```bash
$ docker compose up -d
```

To stop the webserver:
```bash
$ docker compose down
```
## Authors

- [@gschiavodev](https://github.com/gschiavodev)
