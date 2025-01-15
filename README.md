# Project Description

The **Appointments API** is a Laravel-based project designed to manage psychologists, their available time slots, and client appointments. It streamlines the process of scheduling and booking appointments, ensuring a smooth experience for both psychologists and clients.

---

## Key Features

1. **User Authentication (Laravel Sanctum)**
    - Psychologists can register and log in securely.
    - Token-based access for protected endpoints.

2. **Psychologist Management**
    - Create and manage psychologist profiles.
    - Retrieve a paginated list of registered psychologists (protected).

3. **Time Slot Management**
    - Define available time slots for each psychologist.
    - Edit or delete existing time slots (protected).

4. **Appointment Booking**
    - Clients can book an appointment with available time slots.
    - View upcoming appointments (protected).

---

## Technology Stack

- **Language**: PHP
- **Framework**: [Laravel](https://laravel.com/)
- **Authentication**: [Laravel Sanctum](https://laravel.com/docs/sanctum)
- **Database**: MySQL (or any other supported by Laravel)
- **Server**: Apache/Nginx
- **Tools**: Composer, Artisan CLI, PHPUnit (for testing)

---

## Why Use This Project?

- **Simplicity**: Provides a ready-to-use API for appointment booking.
- **Modularity**: Clearly separated modules for psychologists, time slots, and appointments.
- **Security**: Token-based authentication ensures only authorized users can access protected routes.
- **Scalability**: Built with Laravel’s robust framework, making it easy to extend or integrate with other services.

---

## Usage

Below is a brief overview of how to interact with the **Appointments API**.

1. **Register a Psychologist**
    - **Endpoint**: `POST /api/psychologists`
    - **Description**: Create a new psychologist account.
    - **Example Payload**:
      ```json
      {
        "name": "John Doe",
        "email": "johndoe@example.com",
        "password": "securepassword123"
      }
      ```

2. **Login**
    - **Endpoint**: `POST /api/psychologists/login`
    - **Description**: Obtain an authentication token for the psychologist.
    - **Example Payload**:
      ```json
      {
        "email": "johndoe@example.com",
        "password": "securepassword123"
      }
      ```
    - **Note**: The response will include a token to be used in the `Authorization` header for all protected endpoints:
      ```http
      Authorization: Bearer <token>
      ```

3. **Manage Time Slots**
    - **Create Time Slot**
        - **Endpoint**: `POST /api/psychologists/{id}/time-slots`
        - **Description**: Add an available time slot for a given psychologist (protected).
        - **Example Payload**:
          ```json
          {
            "start_time": "2025-01-16 10:00:00",
            "end_time": "2025-01-16 11:00:00"
          }
          ```
    - **View Time Slots**
        - **Endpoint**: `GET /api/psychologists/{id}/time-slots`
        - **Description**: Retrieve all available time slots for a given psychologist (protected).
    - **Update Time Slot**
        - **Endpoint**: `PUT /api/time-slots/{id}`
        - **Description**: Update the start/end times of a specific time slot (protected).
    - **Delete Time Slot**
        - **Endpoint**: `DELETE /api/time-slots/{id}`
        - **Description**: Remove a specific time slot (protected).

4. **Book Appointments**
    - **Endpoint**: `POST /api/appointments`
    - **Description**: Book an appointment for a specific time slot (protected).
    - **Example Payload**:
      ```json
      {
        "time_slot_id": 1,
        "client_name": "Jane Doe",
        "client_email": "janedoe@example.com"
      }
      ```
    - **View Appointments**
        - **Endpoint**: `GET /api/appointments`
        - **Description**: Retrieve a list of upcoming appointments (protected).

5. **Logout**
    - **Endpoint**: `POST /api/psychologists/logout`
    - **Description**: Logout the authenticated psychologist (protected).

---

**Note**: Make sure to include the following header with every protected request:
```http
Authorization: Bearer <token>

## Running the Laravel Project Locally

1. **Clone the Repository**  
   git clone <repository_url>
   cd <repository_directory>

Install Dependencies

composer install

Configure Environment

cp .env.example .env
php artisan key:generate
Update your .env file with the correct database credentials and any other necessary configurations.

Run Migrations

php artisan migrate
This will create all the necessary tables in your database.

Serve the Application

php artisan serve
Your application will typically be accessible at http://127.0.0.1:8000.
