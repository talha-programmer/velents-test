## Project Information
PHP Version: 8.2,
Laravel Version: 10,
OAuth Library: Passport


## Run These Commands After Installation
`php artisan passport:install`

`php artisan storage:link`

## Notes
- Used Password Grant type client of passport for authentication 
- To test the OAuth2 authentication, you can copy the client id and secret generated through `passport:install` and use send them as form data in this route: '/oauth/token' 
- Postman collection will also be available for testing 
