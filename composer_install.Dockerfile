# Use an official PHP image with Composer pre-installed
FROM composer:2

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app

# Run Composer to install the project's dependencies
CMD ["composer", "install"]