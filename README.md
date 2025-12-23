
# ToolTitan – Hardware Shop Management System

Simple PHP backend + JS frontend + Bootstrap CSS.

## Features
- Role-based access: Admin, Supplier, Customer (no self-registration)
- Admin: manage users, products, orders, stock
- Supplier: add products (with images), update stock, view own products
- Customer: view products, place orders, view own order history

## Tech
- PHP (backend)
- MySQL (database)
- Bootstrap (UI)
- Plain JS (frontend)

## Setup
1. Import SQL from `tooltitan.sql` (database name: `tooltitan`)
2. Configure DB connection in `config/db.php`
3. Place app in PHP server (Apache/Nginx) with `assets/images` writable

## Folder structure
