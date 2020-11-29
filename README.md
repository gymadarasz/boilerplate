
setup:
```
git clone https://github.com/gymadarasz/wapp-boilerplate.git my-project
cd my-project
git submodule init
git submodule update
git submodule add https://repository-of-my-project.git src
```
add it to `composer.json` to load project:
```
        "psr-4": {
            ...
            ...
            "MyProject\\": "src/MyProject/",
            "MyProject\\Test\\": "src/tests/"
        }
```
then update composer and create database, but change `my_project` database name to your database name:
```
composer update
(echo 'CREATE DATABASE my_project;USE my_project;' && (cat lib/import.sql || cat src/import.sql)) | mysql -u user -ppassword
```

preferred `config.test.ini` file content:
```
[Mailer]
send_mail = false
save_mail = true
```

then finaly attach your project's routes to the application in `index.php`: (don't forget to fix uses..)
```
$output = (new App($invoker))->getOutput(
    [
        AccountConfig::ROUTES,
        Example::ROUTES,
        ...
        ...
        MyProject::ROUTES,  // <-- Alwas delete route.cache.php when you have changes in routes
    ]
);
```

Before testing:
```
mkdir lib/Library/mails
mkdir lib/Library/log
touch lib/Library/log/app.log
```

testing:
```
./test.sh
```   

usefull commands: (notes only)
```
touch lib/route.cache.php
touch lib/Library/config/config.test.ini
mkdir lib/Library/mails
mkdir lib/Library/log
touch lib/Library/log/app.log

vendor/bin/phan --init --init-level=1
tail -f /var/www/sandbox/my-project/lib/Library/log/app.log
```

developement and PRs:
* Clone this repository then create new branch with proper naming (not main or master!!)
* PRs should ignore changes on files to origin (original file contents are in `dist/` folder):
  - `.gitmodules` project folder (`src/`)
  - `composer.json` project path
  - `index.php` project routes
  - `src/` project folder
