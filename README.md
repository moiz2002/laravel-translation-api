# 🌐 Laravel Translation API

A high-performance Laravel-based translation management API with multi-locale support, tagging, JSON export, and scalability to 100k+ entries.

---

## 🛠️ Requirements

- PHP 8.1+
- MySQL
- Composer
- Laravel 10+
- Laravel Sanctum (optional, for token-based auth)

---

## 🚀 Getting Started
---

### 1. Clone the Repository

git clone https://github.com/moiz2002/laravel-translation-api
cd laravel-translation-api

---

### 2. Install Dependencies

composer install

---

### 3. Set Up Environment

cp .env.example .env

Update your `.env` file with MySQL DB credentials:

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=locale  
DB_USERNAME=root  
DB_PASSWORD=

Generate the app key:

php artisan key:generate

---

### 4. Run Migrations

php artisan migrate

---

### 5. Seed Required Data

Run the basic seeders:

php artisan db:seed --class=LanguageSeeder  
php artisan db:seed --class=TagSeeder

To populate 100,000+ translations for performance testing:

php artisan db:seed --class=LargeTranslationSeeder

(This may take 1–2 minutes)

---
## 📮 POSTMAN COLLECTION
Included in the project as name locales.postman_collection.json in the root
---

## 📮 API Endpoints

All endpoints are under `/api`.

---

## 📮 API Endpoints

GET `/api/translation`.
can have params (locale,tag,key)

GET `/api/translation/export`.
can have params (locale,tag,key)

---

### 🔸 POST `/api/translations`

**Create or update translation**

JSON Body:
```json
{
  "key": "greeting.welcome",
  "description": "Welcome message on homepage",
  "entries": [
    { "locale": "en", "tag": "web", "content": "Welcome to our site!" },
    { "locale": "fr", "tag": "web", "content": "Bienvenue sur notre site !" },
    { "locale": "es", "tag": "mobile", "content": "¡Bienvenido a nuestro sitio!" }
  ]
}


