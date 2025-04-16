# Fund Return Management System
A simple Laravel application to model and manage investment fund returns.


## Features
* Create investment funds with a starting balance.
* Add monthly, quarterly, or yearly returns (percentage-based).
* Specify returns as compounding or non-compounding.
* Track return history.
* Revert previously added returns cleanly.
* Query the calculated fund value at any specific date in the past or present.


## Installation

**Clone the Repository:**

**Install Dependencies:**
    ```bash
    composer install
    ```
    
    ```bash
    npm install
    ```


**Environment Configuration:**
    * Copy the `.env.example` file to `.env`:
    
        ```bash
        cp .env.example .env
        ```
        
    * Generate an application key:
    
        ```bash
        php artisan key:generate
        ```
        
    * Configure your database connection details in the `.env` file (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).


**Database Migrations:**
    * Run the migrations to create the necessary database tables:
    
        ```bash
        php artisan migrate
        ```


**Populate Database With Sample Data:**
    * Seed db with sample data
    
     ```bash
    php artisan db:seed
    ```

## Usage (CLI Commands)
Use Laravel's Artisan console commands to interact with the system.


1.  **Create a Fund:**
    ```bash
    php artisan fund:create "My Growth Fund" 10000.00
    ```
    * Arguments: `name` (string), `initial_balance` (numeric), start_date (optional)
    * Output: Confirmation message with the new Fund ID.


2. **Add a return to a fund**
    ```bash
     php artisan fund:add-return {fund_id} {frequency} {percentage} {date} {--compound=1}
    ```

     ```bash
    # Add a 5% compounding monthly return for fund ID 1 on 2024-01-31
    php artisan fund:add-return 1 monthly 5.0  2024-01-31  --compound=1

    # Add a 2% non-compounding quarterly return for fund ID 1 on 2024-03-31
    php artisan fund:add-return 1 quarterly 2.0 2024-03-31 
    ```
    * Arguments: `fund_id` (integer), frequency (string can should be 'monthly', 'quarterly' or 'yearly'), `percentage` (numeric), `date` (YYYY-MM-DD)    
    * Options:
        * `--compound`: Whether the return is compound (1) or non-compound (0), defaults to 1.
    * Output: Confirmation message with the new Return ID.


3. **Revert a return**
    ```bash
    php artisan fund:revert-return {return_id}
    
    # Revert return for Fund 3
    php artisan fund:revert-return 3
    ```
    * Arguments: `return_id`: The ID of the return to revert


4. **Get fund value at a specific date**
    ```bash
    php artisan fund:get-value {fund_id} {date?}
    ```
       
    ```bash
    # Example: Get fund with id of 1 at 2023-03-25
        php artisan fund:get-value 1 2023-03-15
    ```
    * Arguments: `fund_id`: The ID of the fund
    * Options:
        * `--date`: Optional date (YYYY-MM-DD), defaults to current date


5. **Get a fund statement between two dates**
     ```bash
    php artisan fund:get-statement {fund_id} {start_date} {end_date}
    ```

    ```bash
    # Example: Get a fund statement between 2023-01-01 - 2023-12-31
    php artisan fund:get-statement 1 2023-01-01 2023-12-31
    ```
    * Arguments: `fund_id`:The ID of the fund (interger), `start_date` (YYYY-MM-DD), `end_date` (YYYY-MM-DD)

