# Set the base image
FROM mysql:8.0.34

# Copy dump.sql to the directory
ARG DIRECTORY=/docker-entrypoint-initdb.d
ADD https://raw.githubusercontent.com/kennethclemmensen/kennethclemmensen.dk/master/tests/_data/dump.sql ${DIRECTORY}

# Set permissions on the directory
RUN chmod -R 777 ${DIRECTORY}