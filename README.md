# Running the project

### Change ```.env``` file

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatapp
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=0c370472884045
MAIL_PASSWORD=1e828baa6e13a7
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

PUSHER_APP_ID=1579135
PUSHER_APP_KEY= "23c85b686682373fa34d"
PUSHER_APP_SECRET="5e544fbc0fc8e0245d23"
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER="ap2"

VIRUSTOTAL_API_KEY="6600a9b35e4d1e8477943fa446674edf3eb1527f3f0b4f1a1a4ac4067e49647c"
```

### Change ```resources/views/homepage.blade.php```

```js
var pusher = new Pusher('23c85b686682373fa34d', {
    cluster: 'ap2',
    forceTLS: true
});
```

### Run the migration

```bash
php artisan migrate:fresh
```


