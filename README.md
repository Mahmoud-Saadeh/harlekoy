# Project Setup Instructions

## Requirements
Ensure that the server has the following installed:
- PHP
- A web server (e.g., Nginx)
- Supervisor (or another process manager) for managing queue workers

## Setup Steps

1. **Pull the Changes from GitHub**
   ```bash
   git pull origin <branch-name>
   cd /path-to-your-project
   ```

2. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Create Environment File**
   Create a `.env` file and copy the contents of `.env.example` into it:
   ```bash
   cp .env.example .env
   ```

4. **Database Configuration**
   For this task, we are using SQLite. Ensure that the following line is set in your `.env` file:
   ```env
   DB_CONNECTION=sqlite
   ```

5. **Run Migrations**
   Execute the migrations to set up the database:
   ```bash
   php artisan migrate
   ```

6. **Install Supervisor**
   Install Supervisor using the following command:
   ```bash
   sudo apt-get install supervisor
   ```

7. **Create Supervisor Configuration**
   Create a configuration file for the Laravel queue worker at `/etc/supervisor/conf.d/laravel-worker.conf` with the following content:

   ```ini
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=/usr/bin/php /path-to-your-project/artisan queue:work --sleep=3 --tries=3 --timeout=90
   autostart=true
   autorestart=true
   user=your-web-user
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/path-to-your-project/worker.log
   ```

8. **Reload Supervisor**
   Run the following commands to reload the Supervisor configuration:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start laravel-worker:*
   ```

9. **Add Laravel Scheduler to Crontab**
   Add the Laravel scheduler to the crontab on the staging server:
   - Open the crontab for editing:
     ```bash
     crontab -e
     ```
   - Add the following line to run the scheduler every minute:
     ```bash
     * * * * * /usr/bin/php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
     ```


### Instructions for Use
- Replace `/path-to-your-project` with the actual path to your Laravel project.
- Replace `your-web-user` with the appropriate user that should run the queue workers.
