# 🚀 Product Inventory Management System

## 📌 Overview

This project is a **Product Inventory Management System** developed as part of a PHP Developer assessment.

The system allows users to:

* View product inventory
* Search products by Product ID
* Upload Excel file to update stock
* Process data asynchronously using Laravel Queue

---

## ⚙️ Setup Instructions

### 1. Clone Project

```bash
git clone https://github.com/csfwn/compasia-api.git
cd project-folder
```

---

### 2. Install Dependencies

```bash
composer install
npm install
```

---

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update DB config in `.env`

---

### 4. Run Migration & Seeder

```bash
php artisan migrate --seed
```

---

### 5. Run Queue

```bash
php artisan queue:work
```

---

### 6. Run Server

```bash
php artisan serve
npm run dev
```

---

## 🛠️ Tech Stack

* **Backend:** PHP Laravel 10
* **Frontend:** Vue 3
* **Database:** MySQL
* **Architecture:** RESTful API + Queue

---

## 🎯 Features

### ✅ Product Listing

* Display all products from database
* Backend pagination implemented

---

### ✅ Search Filter

* Filter by Product ID
* Server-side filtering via API

---

### ✅ Excel Upload

* Upload `.xlsx` file
* Process data in background using Laravel Queue

---

### ✅ Inventory Update Logic

| Status | Action          |
| ------ | --------------- |
| Sold   | Deduct quantity |
| Buy    | Add quantity    |

---

### ✅ Flexible Excel Format (Advanced)

* Auto-detect column using header (no hardcoded index)
* Supports dynamic Excel structure

---

## 📊 Database Structure

### product_master_lists

| Column     | Type         |
| ---------- | ------------ |
| id         | PK           |
| product_id | int (unique) |
| type       | string       |
| brand      | string       |
| model      | string       |
| capacity   | string       |
| quantity   | int          |

---

## 🔌 API Endpoints

### 📥 Get Products

```http
GET /api/product-master-lists
```

Query Params:

```
product_id=4450
page=1
```

---

### 📤 Upload Excel

```http
POST /api/product-master-lists/upload
```

Body:

```
form-data → file (.xlsx)
```

---

## 🔄 System Flow

```
User Upload File (Vue)
        ↓
Laravel API Store File
        ↓
Dispatch Queue Job
        ↓
Queue Worker Process Excel
        ↓
Update Database
        ↓
Frontend Fetch Updated Data
```

---

## 🧠 Use Case

### 1. View Product List

User accesses the system and views product inventory with pagination.

---

### 2. Search Product

User enters Product ID → system filters data via API.

---

### 3. Upload Excel File

User uploads product_status_list.xlsx → system processes file asynchronously.

---

### 4. Update Inventory

* If status = Sold → quantity deducted
* If status = Buy → quantity added

---

### 5. View Updated Data

User refreshes page → updated quantity displayed.

---

## 📁 Sample Excel Format

| Product ID | Types      | Brand | Model     | Capacity | Status |
| ---------- | ---------- | ----- | --------- | -------- | ------ |
| 4450       | Smartphone | Apple | iPhone SE | 2GB/16GB | Sold   |

> Note: Quantity is assumed as **1 per row** (since not provided).

---

## ⚠️ Error Handling

* Invalid file → rejected
* Missing column → logged
* Product not found → skipped
* Negative quantity → prevented

---

## 🚀 Future Improvements

* Progress tracking for queue job
* Bulk update query for performance
* Real-time update (WebSocket)
* Upload history tracking

---

## 💬 Conclusion

This project demonstrates:

* Clean RESTful API design
* Efficient background processing using Laravel Queue
* Flexible data handling (Excel integration)
* Scalable and maintainable architecture

---

## 👨‍💻 Author

Developed by: *Safwan Ismail*
