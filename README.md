# Mini Blog API

A cleanly structured REST API built with **Laravel** and **Sanctum** authentication.  
This API allows user authentication, article management, category management, public listing of published articles, and rate-limiting.

---

## **Features**
- **User Authentication** (login, logout, user info) with token-based access.
- **Article Management** (create, update, delete, soft delete) for authenticated users.
- **Category Management** (CRUD).
- **Public Article Listing** with filters (no authentication required).
- **API Rate Limiting** (per-minute and daily).
- **Database seeder** with sample user, categories, and articles.
- **Postman Collection** for testing all endpoints.

---

## **Requirements**
- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & npm (optional, for front-end scaffolding)

---

## **Installation**
### 1. Clone the Repository
```bash
git clone https://github.com/zabbir007/mini-blog-api.git
cd mini-blog-api
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Create Environment File
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
Then configure your database credentials inside `.env`:
```
DB_DATABASE=mini_blog
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

This will create:
- A test user:
  - **Email:** `test@gmail.com`
  - **Password:** `password`
- Sample categories and articles.

---

## **Running the Server**
Start the Laravel development server:
```bash
php artisan serve
```
API will be available at:  
```
http://127.0.0.1:8000
```

---

## **Authentication**
Use **Sanctum** for token-based authentication.

### **Login**
```http
POST /api/auth/login
```
#### Example Request Body:
```json
{
  "email": "test@gmail.com",
  "password": "password"
}
```
#### Example Response:
```json
{
  "token": "1|xxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@gmail.com"
  }
}
```

Use the returned `token` as a **Bearer token** in all authenticated requests.

---

## **API Endpoints**
### **Auth**
| Method | Endpoint         | Description            |
|--------|------------------|------------------------|
| POST   | `/api/auth/login` | Login and get token    |
| GET    | `/api/auth/me`    | Get logged-in user info |
| POST   | `/api/auth/logout`| Logout and invalidate token |

### **Articles (Authenticated)**
| Method | Endpoint                  | Description                  |
|--------|---------------------------|------------------------------|
| GET    | `/api/articles/mine`      | List logged-in user's articles |
| POST   | `/api/articles`           | Create a new article         |
| GET    | `/api/articles/{id}`      | View specific article (owner only) |
| PUT    | `/api/articles/{id}`      | Update article (owner only)  |
| DELETE | `/api/articles/{id}`      | Soft delete article          |

### **Public Articles (No Auth)**
| Method | Endpoint                        | Description                 |
|--------|---------------------------------|-----------------------------|
| GET    | `/api/articles`                 | List published articles (with filters: `category`, `user_id`) |
| GET    | `/api/articles/public/{id}`     | View a published article    |

### **Categories (Authenticated)**
| Method | Endpoint               | Description         |
|--------|------------------------|---------------------|
| GET    | `/api/categories`      | List all categories |
| POST   | `/api/categories`      | Create category     |
| PUT    | `/api/categories/{id}` | Update category     |
| DELETE | `/api/categories/{id}` | Delete category     |

---

## **Rate Limiting**
- **Per-minute limit:** 60 requests.
- **Daily limit:** 1000 requests per user (or per IP for unauthenticated users).
- Exceeding limits returns:
```json
{
  "error": "API rate limit exceeded. Try again later."
}
```

---

## **Postman Collection**
A ready-to-use Postman collection is available:
```
MiniBlogAPI.postman_collection.json
```
You can import it into Postman to test all endpoints.

---

## **Example Usage with CURL**
### Create Article
```bash
curl -X POST http://127.0.0.1:8000/api/articles -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/json" -d '{
  "title": "My New Article",
  "body": "This is my article content",
  "status": "published",
  "category_id": 1
}'
```

---

## **License**
This project is open-source and free to use.
