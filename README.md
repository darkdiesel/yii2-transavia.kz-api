<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 User Profiles</h1>
    <br>
</p>

## Table of content
- [Project Structure](#project-structure)
- [Install](#install)
- [Run](#run-application)
- [Migration](#migration)
- [Useful commands](#useful-commands)

## Project structure

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

## Install

1. Install via command: ```composer create-project --prefer-dist yiisoft/yii2-app-advanced yii2-transaviakz-api```
2. Init : ```php init```
3. Create DB and update **common/config/main-local.php**
4. Run migration ```php yii migrate```

## Run application:

1. Use docker, vagrant or setup 2 hosts `admin` to `/backend/web` and `front` to `frontend/web`
2. Open site with browser and go to sign up page.
3. Fill fields and create user.
4. Go to `frontend\runtime\mail\` and open `*.eml file`
5. Copy confirm url from file and made some next modification to it:
    - for example we have next url:
   ```
    http://yii2-transaviakz-api.loc/index.p=
    hp?r=3Dsite%2Fverify-email&amp;token=3DL13CX6Fro9E_MYdJ8gGRK_0GCleoRpBa_170=
    2598408
   ```
- delete soft line breaks ‘=’ and newlines to create a single line with the line below
- change ‘=3D’ to ‘=’ // after r and after token
- change ‘&amp;‘ to '&'
- change ‘%2F‘ to ‘/‘
- and we have `http://yii2-transaviakz-api.loc/index.php?r=site/verify-email&token=3DL13CX6Fro9E_MYdJ8gGRK_0GCleoRpBa_1702598408`
- put this url to browser and if everything is ok your user will be verified

## Useful commands:

## Migration:
* Run migration ```php yii migrate```
* Revert last migration ```yii migrate/redo```
* Refresh migration ```yii migrate/fresh ```