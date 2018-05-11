# <p align="center">Phanon - The Virtuoso Programmer</p>

## About Phanon
<p>Phanon is a web platform to facilitate teaching introductory Computer Science courses. It is programmed in PHP using the <a href="https://laravel.com/">Laravel</a> framework. Currently, only the Python language is supported, but we are looking to expand to allow the use of C# and JavaScript. It uses <a href="https://codemirror.net/">CodeMirror</a> for editing code on the website and <a href="http://www.skulpt.org/">Skulpt</a> for transpiling the Python code to JavaScript.</p>

## Setting up the Development Environment

### Software Requirements
<ul>
    <li>Apache Server (XAMPP)</li>
    <li>Text Editor (Visual Studio Code)</li>
    <li>MySQL Database (XAMPP)</li>
    <li>Composer</li>
</ul>

### Install An Apache Server
<p>The easiest way to set up an Apache server is to use <a href="https://www.apachefriends.org/index.html">XAMMP</a>. The installer is straight forward and all the default values can be used.</p>

### Install A Text Editor
<p>Any text editor can be used but I have found <a href="https://code.visualstudio.com/">Visual Studio Code</a> to be very nice to use.</p>

### Download the Phanon Repository
<p>Clone this repository and unzip it. Move it to wherever you want it. Putting it in the htdocs folder in the XAMPP installation folder makes things a little easier. It is usually located at "C:\xampp\htdocs".</p>

### Install Composer
<p>Since Phanon uses the Laravel PHP framework, <a href="https://getcomposer.org/">Composer</a> needs to be installed. Installation is quick and painless and all the default values can be kept.</p>

### Install Composer Within the Project Directory
<p>In order for the website to work, you must install composer in the actual project directory. On Windows, open the Command Prompt. Navigate to where you moved the Phanon repository you downloaded. Type in the command: <pre align="center">composer install</pre></p>

### Create the Database
<p>In order to create the database, we will use PHPMyAdmin that is packaged with XAMMP. It is assumed that your document root hasn't been changed from the default. Open the XAMPP control panel and start the Apache server and the MySQL connection. In a web browser, navigate to <a href="https://localhost/phpmyadmin">localhost/phpmyadmin</a>. This should take you to the PHPMyAdmin dashboard. In the left column, click the "New" option. Enter a database name and click the "Create" button.</p>

### Initialize the .env File
<p>In Laravel, there needs to be a .env file in the root of the project directory that defines various things about the project including it's name and database credentials. Included in the Phanon repository is a .env_example file that can be used as a starting point. Copy it and rename the new copy .env. One of the required fields is the APP_KEY field near the top of the file. In order to generate a key, type the following command into the command prompt: <pre align="center">php artisan key:generate</pre>The name of the database you created  will need to be entered on the DB_DATABASE line. If you created a user with a password for your database, enter it on the DB_USERNAME and DB_PASSWORD lines. If you haven't created a user, enter "root" for the DB_USERNAME and leave the DB_PASSWORD blank.</p>

### Create the Database Schema
<p>There are two methods to do this. The first method uses an artisan command to run any migrations that have been created for the project. The second method has you interact with the database directly through PHPMyAdmin.</p>

##### Method One (Easiest Method)
<p>Enter the following command in the command prompt:<pre align="center">php artisan migrate</pre></p>

##### Method Two
<p>Open <a href="https://localhost/phpmyadmin">localhost/phpmyadmin</a> in the browser. Across the navbar at the top, select "Import". On the page that opens, click the "Choose File" button. Included in the root directory of the Phanon repository is a _SQL folder. Contained in there are files to recreate the database schema. You can either import the entire schema at once using the "phanon_db.sql" file in the "Entire Dump" folder or you can import specific tables from the "Individual Tables" folder. Once you selected the file(s) you want to import, click the "Go" button at the bottom of the page.</p>

### Conclusion
<p>That should be all the setup that needs to happen to start developing from this repo. In your browser, navigating to the public folder of the Phanon directory will take you to the actual webpage. From here, you can create users, various objects, or run some Python code in the Sandbox.</p>

