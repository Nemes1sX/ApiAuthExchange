# Exchange API with OAuth 
Show currency exchange values FROM-TO work with API auth (OAuth)
## Installation instructions
1. Migrate ```php artisan migrate```
2. Install passport ```php artisan passport:install```
3. Deploying passport keys ```php artisan passport:keys```
4. Launch 2 instances. 
* Main app ```php artisan serve```
* App which serves for token granting ```php artisan serve --port=8001```
