
To get started, first clone the project using the following command: git clone https://github.com/tufan3/student-task-management-system.git. 

Then navigate into the project directory with cd student-task-management-system. 

Install all the dependencies using composer install. 

Next, copy the example environment file and generate the application key using the commands: cp .env.example .env and php artisan key:generate. 

After that, run the migrations with php artisan migrate to set up the database tables. 

Now, start the development server by running php artisan serve.

To enable queued email notifications, run the command php artisan queue:work. 

For image uploads to work properly, create a symbolic link using php artisan storage:link. 

Finally, to send scheduled announcements manually, use the command php artisan send:scheduled-announcements.

