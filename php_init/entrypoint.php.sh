#!/bin/bash

# Wait for MySQL to be initialized
while [ ! -f /shared_data/mysql_init_complete ]; do
  echo "Waiting for MySQL initialization to complete..."
  sleep 1
done

# MySQL initialization is complete
echo "MySQL initialization is complete!"

# Check if PHP scripts have already been run
if [ ! -f /shared_data/php_init_complete ]; then

  # Perform the connection check three times
  for i in 1 2 3; do

    # Wait for a successful connection to the MySQL server
    while ! php -r "new mysqli('$MYSQL_HOST', '$MYSQL_USER', '$MYSQL_PASSWORD');" >/dev/null 2>&1; do

      echo "Waiting for a connection to the MySQL server..."
      sleep 1
      
    done

    sleep 2;

  done

  # MySQL server is ready
  echo "MySQL server is ready!"

  # Execute all PHP scripts in /docker-entrypoint-initphp.d
  for file in /docker-entrypoint-initphp.d/*.php; do
    php "$file"
  done

  # Mark PHP scripts as done
  touch /shared_data/php_init_complete

fi

# Execute the main command (e.g., start Apache)
exec "$@"
