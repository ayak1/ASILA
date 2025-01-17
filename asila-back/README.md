
# ASILA Backend 
ASILA Backend is the server-side application for the ASILA tourism platform, developed with **Laravel**. It provides APIs, authentication, and database management for the platform.

- Follow these steps to set up and run the backend locally:

1. Clone the repository and navigate to the backend directory:

   ```bash
   git clone https://github.com/ayak1/ASILA.git
   cd ASILA/asila-back
2. Install the required dependencies:
    ```bash
    composer install
3. Copy the example .env file and configure it:
    ```bash
    cp .env.example .env
4. Generate an application key:
    ```bash
    php artisan key:generate
5. Configure the .env file:
- Set up database connection details.
- Add any additional environment variables required for your setup.
6. Run database migrations:
    ```bash
    php artisan migrate
7. Start the development server:
    ```bash
    php artisan serve
- The backend will now be accessible at http://localhost:8000.