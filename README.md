# INF1005-P3-07-Project

## Local Setup Instructions

Pull the latest changes from `main`. Then, everyone must run `create_products_table.sql` in their local phpMyAdmin to set up the products table. Follow these exact steps:

1. Copy and open `http://localhost/phpmyadmin` in your browser.
2. If the `maison_reluxe` database does not exist, create it first:
   - In phpMyAdmin, click **New** on the left sidebar.
   - Type `maison_reluxe` as the database name, set the collation to `utf8mb4_unicode_ci`, then click **Create**.
3. Select the `maison_reluxe` database from the left sidebar.
4. Go to the **SQL** tab at the top.
5. Open `create_products_table.sql`, copy its entire contents, paste it into the SQL box, and click **Go**.

This will create the `maison_reluxe_products` table and insert all 39 products.

### Database Connection and Running the Site

- After setting up the database, update your local `php/db_connect.php` file with your own credentials. If you're on XAMPP, use `root` with an empty password.
- **Do NOT push your local `php/db_connect.php` changes to GitHub.**
- Launch the site via XAMPP by navigating to `http://localhost/your-folder-path/products.php` in your browser.
- **Do not use VS Code's Live Server**, as it doesn't support PHP.