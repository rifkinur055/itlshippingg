# ITL Shipping (Railway Deploy)

Deploy this PHP app on Railway with a MySQL plugin.

## Railway steps

1. Create a new Railway project
2. Add **MySQL** plugin
3. Deploy this repo (Dockerfile)
4. Import schema: use your local mysql client and Railway MySQL env vars

## Local import example

Download schema from `app_shipping/db_itl_shipping.sql`.

```bash
mysql -h "$MYSQLHOST" -P "$MYSQLPORT" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" "$MYSQLDATABASE" < app_shipping/db_itl_shipping.sql
```

App will be available at `/` (redirects to `/app_shipping/login.php`).
