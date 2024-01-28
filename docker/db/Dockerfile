# Set the base image
FROM mysql:8.3.0

# Copy dump.sql to the directory
ARG DIRECTORY=/docker-entrypoint-initdb.d
ADD https://raw.githubusercontent.com/kennethclemmensen/kennethclemmensen.dk/master/tests/wpbrowser/Support/Data/dump.sql ${DIRECTORY}

# Set permissions on the directory
RUN chmod -R 777 ${DIRECTORY}