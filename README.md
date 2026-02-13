# ☕ Urban Grind | Mumbai Edition

Urban Grind is a premium, modern Café Management System designed for the vibrant urban culture of Mumbai.  
It features a high-performance administrative dashboard for managing reservations, menu items, seating, and customer inquiries — along with a visually engaging customer-facing website.

Built for performance, simplicity, and elegant UI.

---

## 🚀 Key Features

### 👥 Customer Experience

- ✨ **Artisan Landing Page** – Interactive UI with smooth animations and modern typography.
- 🍽️ **Mumbai-Localized Menu** – Includes regional favorites like:
  - South Indian Filter Coffee  
  - Vada Pav Sliders  
  - Masala Chai  
  - Cold Coffee Specials  
- 📅 **Real-Time Reservations** – Seamless table booking system.
- 📨 **Support Portal** – Contact form for event inquiries and feedback.

---

### 🛠️ Admin Management Panel

- 📊 **Professional Dashboard** – Overview of café performance and pending tasks.
- 📋 **Reservation Control**
  - Approve bookings  
  - Cancel reservations  
  - Manage customer details  
- 🍴 **Menu Management**
  - Add / Edit / Delete menu items  
  - INR (₹) pricing support  
- 🪑 **Seating Management**
  - Table capacity control  
  - Availability management  
- 📥 **Communications Hub**
  - View and manage customer messages  

---

## 🛠️ Technical Stack

**Frontend**
- HTML5  
- CSS3  
- JavaScript  

**Backend**
- PHP 8.x  

**Database**
- MySQL / MariaDB  

**Fonts**
- Inter  
- Plus Jakarta Sans  

---

## 💻 Installation & Setup Guide (XAMPP)

### 1️⃣ File Placement

1. Download the project source code.
2. Navigate to your XAMPP installation directory:
```C:\xampp\htdocs\```
3. Create a new folder named:
###Cafe
4. Paste all project files inside that folder.
Final structure:
```C:\xampp\htdocs\Cafe\```

---

### 2️⃣ Database Configuration

1. Open XAMPP Control Panel.
2. Start:
   - Apache  
   - MySQL  

3. Open your browser and go to:
```
http://localhost/phpmyadmin/
```
4. Click **New** and create a database named:
## cafe_management
5. Select the `cafe_management` database.
6. Click the **Import** tab.
7. Choose the file:
*admin/schema.sql*
8. Click **Import**.

---

### 3️⃣ Database Connection

Ensure your `config/db.php` file contains:

```php
<?php
$host = 'localhost';
$db   = 'cafe_management';
$user = 'root';
$pass = '';
?>
```

(Default XAMPP credentials.)

## 🔑 Login Credentials
🛠️ Admin Portal

Access admin panel:
```http://localhost/Cafe/admin/login.php```

**Admin Credentials**

- Username: `admin`
- Password: `password`

---

### 👤 Existing User Account

You can also login using an existing registered user:

- Email: `abc@1.gmail.com`
- Password: `123456`

---

## 📂 Project Structure

```bash
Cafe/
├── admin/                # Admin Management Panel
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── manage-menu.php
│   ├── manage-tables.php
│   ├── reservations.php
│   └── schema.sql
│
├── assert/               # CSS and JS files
│   ├── css/style.css
│   └── js/main.js
│
├── config/               # Database connection
│   └── db.php
│
├── includes/             # Reusable components
│   ├── header.php
│   └── footer.php
│
├── index.php             # Landing page
├── menu.php              # Customer menu
├── contact.php           # Contact form
├── reserve.php           # Reservation system
├── login.php             # User login
├── register.php          # User registration
└── logout.php
```
---
### 📌 Important Notes

Make sure Apache and MySQL are running before accessing the project.

- If the project does not load, verify:
- Folder name is Cafe
- Database name is cafe_management
- schema.sql is imported correctly

## 🤝 Contribution

Contributions are welcome and appreciated!
## 📸 Screenshots

### 🏠 Landing Page
![Landing Page](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/cafe1.png)

---

### 📊 Admin Dashboard
![Admin Dashboard](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/admin.png)

---
### 🏠 Table Arrengement
![Table Arrengement](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/table.png)

---

### 🍽️ Menu Management
![Menu Management](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/menumanage.png)

---

### 🍽️ User Sign Up
![User Sign Up](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/signup.png)

---

### 🍽️ User Dashboard
![User Dashboard](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/user.png)

---

### 🍽️ Menus
![Menus](https://raw.githubusercontent.com/PatilParas05/Cafe-Management/main/Cafe/screenshot/Menu.png)



