# Project Name

## Installation

### Prerequisites

- PHP 8.2
- Composer
- Node.js (with npm)

### Setup

1. **Install PHP Dependencies**:
    ```bash
    composer install
    ```
2. **Install Node Dependencies**:
    ```bash
    npm install
    ```
3. **Copy the `.env.example` file to `.env` and configure your environment variables:**
4. **Generate an application key:**
    ```bash
    php artisan key:generate
    ```
5. **Run the database migrations:**
    ```bash
    php artisan migrate
    ```
   
6. **Store the application key:**
    ```bash
    php artisan storage:link
    ```
   
7**Start the development server:**
    ```bash
   composer run dev
    ```
