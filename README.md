# Loan Application
It is an app that allows authenticated users to go through a loan application:
* Customer can create a loan
* Admin can approve the loan
* Customer can view loan belong to him
* Customer add a repayments

## Installation

The whole application consists of three services: a nginx server, an app and a database mysql. 
These services are managed via docker-compose and can be installed and run by the following steps:

```console
# clone the repository
$ git@github.com:dinhphien/loan-application.git

$ cd loan-application/

# install dependencies and start the whole application
$ make install
$ make startup
```

## Testing
There is a postman collection in postman folder which can be imported into Postman application, 
so you can test this application via Postman.

Before start using loan functionalities, you can register a customer user. If you don't want to then you can use 
preconfigured users which are phien@gmail.com/phien (customer user) and admin@gmail.com/admin (admin user).

## Architecture
The app service is built using Laravel framework with MVC architectural pattern.
Two additional layers: Service layer and Domain layer are added for clear and separation.
Instead of messing up Controller layer with business logic.

In Service layer, Publish/Subscribe pattern is applied to decouple the main workflow from other flows that act upon that.

## Quality tools
To ensure quality code of this project, Pint and Phpstan are used for code style fixer and static code analysis.
