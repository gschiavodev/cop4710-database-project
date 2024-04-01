#!/bin/bash

# Set the working directory
cd /docker-entrypoint-initphp.d

# Wait for MySQL to be initialized
wait_for_mysql_init() 
{

  local timeout=90
  local wait_time=0

  # Wait for the MySQL initialization to complete
  while [ ! -f /shared_data/mysql_init_complete ]; do
    if [ $wait_time -ge $timeout ]; then
      echo -e "ENTRYPOINT: Timeout waiting for MySQL initialization!\n"
      exit 1
    fi

    # Wait for 1 second
    echo -e "ENTRYPOINT: Waiting for MySQL initialization to complete...\n"
    wait_time=$((wait_time+1))
    sleep 1

  done

  echo -e "ENTRYPOINT: MySQL initialization is complete!\n"

}

# Wait for a successful connection to the MySQL server
wait_for_mysql_connection_and_query() 
{

  local timeout=90
  local wait_time=0

  # Wait for a successful connection to the MySQL server and successful query execution
  while ! php ./utility/database_connection_check.php; do

    # Check if the timeout has been reached
    if [ $wait_time -ge $timeout ]; then
      echo -e "ENTRYPOINT: Timeout waiting for a connection to the MySQL server and successful query execution!\n"
      exit 1
    fi

    # Wait for 1 second
    echo -e "ENTRYPOINT: Waiting for a connection to the MySQL server and successful query execution...\n"
    wait_time=$((wait_time+1))
    sleep 1

  done

  # MySQL server is ready to handle queries
  echo -e "ENTRYPOINT: MySQL server is ready to handle queries!\n"

}

# Execute all PHP scripts in /docker-entrypoint-initphp.d
execute_php_scripts() 
{

  # Execute all PHP scripts in /docker-entrypoint-initphp.d
  for file in ./init/*.php; do
    php "$file"
  done

  # Mark PHP scripts as done
  touch /shared_data/php_init_complete

}

# Check if PHP scripts have already been run
if [ ! -f /shared_data/php_init_complete ]; then
  wait_for_mysql_init
  wait_for_mysql_connection_and_query
  execute_php_scripts
fi

# Execute the main command (e.g., start Apache)
exec "$@"
