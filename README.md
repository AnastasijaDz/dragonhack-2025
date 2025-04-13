# 🌿 EcoStock – Backing Farms, Building Futures

Looking for a stable investment? Want to support local, eco-friendly projects? You’re in the right place.

## 🚜 What is EcoStock?
EcoStock is a platform that connects investors with small-scale, sustainable agricultural projects. We offer access to proven, long-term investment opportunities by partnering with experienced local producers looking to expand their operations.

## ✅ Key Features

* Smart investing, zero paperwork
We handle the logistics — you just browse, invest, and track your returns.

* Live portfolio tracking
Visualize your profits and allocations over time with interactive graphs and reports.

* ROI Calculator
Estimate potential returns which use real past data and break-even points before committing funds.

* Tokenized ownership
Easily buy, sell, and transfer your shares in projects with seamless digital ownership.

## 🔐 Investor Safety
Your investments are backed by a transparent model and direct communication with project owners. Every investment is optionally covered by an insurance policy for natural disasters, protecting up to 80% of your invested amount.

# EcoStock Technical Documentation

## System Overview

**EcoStock** is a Laravel-based web application that connects investors with sustainable agricultural projects. The platform provides:

- Investment management  
- Portfolio tracking  
- ROI calculations  
- Tokenized ownership features  

## Tech Stack

- **Backend Framework:** Laravel (PHP)  
- **Database:** MySQL 8.0  
- **Web Server:** Nginx  
- **Frontend:** Laravel Blade templating with JavaScript  
- **Containerization:** Docker  
- **Package Manager:** Composer (PHP), NPM (Node.js)  

## System Architecture

The application is containerized using Docker with the following services:

### Laravel App Container (`app`)

- Runs PHP-FPM and the Laravel application  
- Handles API endpoints, business logic, and data management  

### Web Server (`nginx`)

- Serves the application on port **80**  
- Handles HTTP requests and routes them to the PHP-FPM service  

### Database (`mysql`)

- MySQL 8.0 database for storing application data  
- Persists data using a named volume  

### Database Management (`phpmyadmin`)

- Web interface for managing the MySQL database  
- Accessible at [http://localhost:8080](http://localhost:8080)  

### Node.js Environment (`node`)

- Provides **Node.js 20** for frontend asset compilation  
- Used for JavaScript dependency management and build processes  

## Development Setup

### Prerequisites

- Docker & Docker Compose  
- Git  

### Installation Steps

1. Clone the repository:
   ```bash
   git clone <repository-url>


