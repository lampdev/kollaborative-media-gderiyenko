# Laravel API with Job Queue and Event System

This project demonstrates how to create an API endpoint in Laravel that processes form submissions, validates data, dispatches a job to save data to a database, and triggers an event upon successful data saving.

## Contents
1. [Setup Instructions](#setup-instructions)
2. [Running Migrations](#running-migrations)
3. [API Endpoint](#api-endpoint)
4. [Error Handling](#error-handling)
5. [Testing](#testing)
6. [Unit Test](#unit-test)
7. [Handling Job Processing Errors](#handling-job-processing-errors)
8. [Custom Event vs. Model Event](#custom-Event-vs-model-event)

## Setup Instructions

### 1. Clone the Repository

### 2. Install Dependencies

Ensure you have Composer installed, then run:
```bash
composer install
```

### 3. Configure Environment

Copy the .env.example file to .env and update environment settings as needed:
```bach
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration

Update the .env file with your database connection details:
```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 5. Run Migrations

Run the following command to create the necessary tables in the database:
```bash
php artisan serve
php artisan migrate

// sail:
// ./vendor/bin/sail up -d
// ./vendor/bin/sail artisan migrate
```

## API Endpoint
### Endpoint: ` /submit `
This endpoint accepts a POST request with the following JSON payload structure:
```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "message": "This is a test message."
}
```

### Data Validation
 - name: Required
 - email: Required, must be a valid email address
 - message: Required

If validation fails, the API will return a 422 Unprocessable Entity response with details of the validation errors.

### Job Queue

Upon receiving a valid request, the data is not immediately saved to the database. Instead, a job is created to handle the asynchronous saving of data.

### Event

After successfully saving data, the SubmissionSaved event is dispatched, which logs a message including the name and email of the submission.

### Error Handling
Data Validation Errors
If any required fields are missing or invalid, the API will return a 422 Unprocessable Entity response with a JSON payload detailing the validation errors:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "email": ["The email must be a valid email address."],
        "message": ["The message field is required."]
    }
}
```
### Job Processing Errors
If an error occurs during job processing, the API will log the details and the job will fail. The API will return a 500 Internal Server Error response if an error is detected during request processing.

## Testing

### Unit Test
A simple unit test is included to verify the functionality of the LogSubmissionSaved event listener.

To run the unit test:

```bash
php artisan test

// sail:
// ./vendor/bin/sail artisan test
```

### Handling Job Processing Errors
In this API, the data submitted by the client is processed asynchronously through a Laravel job queue. This approach is designed to ensure that the API remains responsive and can handle large volumes of requests without delay. However, because the job processing happens asynchronously (i.e., in the background), it's not possible to return any errors that occur during the job execution in the API response. Doing so would contradict the purpose of using a job queue, which is to decouple the immediate response from the longer-running background tasks.

To ensure that any errors during job processing are captured and handled, we log the errors to the application logs. This allows us to track and diagnose any issues without disrupting the API's functionality or user experience.

### Custom Event vs. Model Event

In this project, we implemented a custom event (SubmissionSaved) that is triggered after the data is successfully saved to the database by the job. This event is handled by a listener that logs a message with the name and email of the submission. While this approach follows the Event-Driven Development (EDD) pattern, it’s important to note that Laravel also offers built-in model events, such as created, updated, and deleted. These model events are automatically triggered when certain actions are performed on the model, making them a convenient and efficient way to handle related tasks.

In many cases, using model events might be a better choice since they are already integrated into the framework and provide a standardized way to handle common actions. However, creating custom events, as we did here, allows for greater flexibility and can be more appropriate in scenarios where you need to trigger events outside the context of model operations or want to maintain a clear separation of concerns.

In summary, while using model events is often preferable for simplicity and built-in support, creating custom events as we did here aligns with the project instructions and demonstrates how to implement custom event-driven logic in Laravel.