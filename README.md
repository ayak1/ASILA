ASILA is a web application designed to enhance your travel experience in Türkiye by providing comprehensive tourism services. The project comprises a frontend built with Nuxt.js and a backend developed using Laravel.

Project Structure:

Frontend: Located in the asila-front directory, this part of the application is built with Nuxt.js, a framework for creating Vue.js applications.

Backend: Found in the asila-back directory, the backend is developed using Laravel, a PHP framework for web artisans.

Deployment Instructions:

Note: Ensure you have Node.js, npm, PHP, Composer, and a web server (e.g., Apache or Nginx) installed on your system.

Clone the Repository:

bash
Copy code
git clone https://github.com/ayak1/ASILA.git
cd ASILA
Backend Setup (Laravel):

Navigate to the backend directory:
bash
Copy code
cd asila-back
Install dependencies:
bash
Copy code
composer install
Copy the example environment file and set your environment variables:
bash
Copy code
cp .env.example .env
Generate an application key:
bash
Copy code
php artisan key:generate
Configure your .env file with database credentials and other necessary settings.
Run database migrations:
bash
Copy code
php artisan migrate
Start the Laravel development server:
bash
Copy code
php artisan serve
Frontend Setup (Nuxt.js):

Open a new terminal window and navigate to the frontend directory:
bash
Copy code
cd asila-front
Install dependencies:
bash
Copy code
npm install
Start the Nuxt.js development server:
bash
Copy code
npm run dev
Access the Application:

The backend should be running at http://localhost:8000.
The frontend should be running at http://localhost:3000.
Additional Resources:

Nuxt.js Documentation: For more information on configuring and deploying the frontend, refer to the Nuxt.js documentation.

Laravel Documentation: For detailed guidance on Laravel, visit the Laravel official documentation.

By following these steps, you can set up and run the ASILA project locally, allowing you to explore its features and functionalities.