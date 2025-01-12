# ASILA Frontend
This is the frontend of the ASILA project, a tourism platform designed to enhance travel experiences in Türkiye. The frontend is built using **Nuxt.js**, a framework for building Vue.js applications with server-side rendering capabilities.
## Features
- User-friendly interface for exploring tourism services.
- Multi-language support for seamless navigation.
- Responsive design for optimal performance on all devices.
## Prerequisites
Before setting up the project, ensure you have the following installed:
- Node.js (v16 or higher)
- npm (v7 or higher)
## Installation
1. Clone the repository and navigate to the frontend directory:
   ```bash
   git clone https://github.com/ayak1/ASILA.git
   cd ASILA/asila-front
2. Install dependencies:
    ```bash
    npm install
3. Start the development server:
    ```bash
    npm run dev
4. Access the application in your browser at http://localhost:3000.

## Build for Production
- To build the frontend for production:
    ```bash
    npm run build
- This generates static files in the .output directory. You can deploy these files to a static hosting platform.
## Configuration
- Update environment variables in a .env file if required.
- Customize settings in nuxt.config.js for additional configurations.
## Deployment
 For deployment, use a hosting service like Vercel, Netlify, or a custom server. For example:
- Build the project:
    ```bash
    npm run build
- Start the production server:
    ```bash
    npm start