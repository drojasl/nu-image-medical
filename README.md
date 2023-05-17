# nu-image-medical

# Steps to run the application
1. Copy the *.env.example* file to create the *.env*
2. Add your email to *MAIL_SUPPORT_ADDRESS=* in *.env*
3. Add the value for *MAIL_PASSWORD=* (sent via email)
4. Run *composer install*
5. Run *npm install*
6. Run Vue app: *npm run dev*
7. Run migrations: *php artisan migrate*
8. Run Laravel server: *php artisan serve*
9. Open in browser http://127.0.0.1:8000/
10. To run test use *php artisan test*