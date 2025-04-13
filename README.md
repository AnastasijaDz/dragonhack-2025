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

# 🛠️ EcoStock Technical Documentation

## Tech Stack

- **Backend Framework:** Laravel (PHP)  
- **Database:** MySQL 8.0  
- **Web Server:** Nginx  
- **Frontend:** Laravel Blade templating with JavaScript  
- **CSS Framework:** Tailwind CSS  
- **Containerization:** Docker  
- **Package Manager:** Composer (PHP), NPM (Node.js)

## System Architecture

The application is containerized using Docker with the following services:

- **Laravel App Container (app):** PHP-FPM running the Laravel application, handling API endpoints and business logic.
- **Web Server (nginx):** Serves the application on port 80, routing HTTP requests to PHP-FPM.
- **Database (mysql):** MySQL 8.0 for data storage with persistent volumes.
- **Database Management (phpmyadmin):** Web interface for managing MySQL, accessible at [http://localhost:8080](http://localhost:8080).
- **Node.js Environment (node):** Node.js 20 for asset compilation and JavaScript dependency management.

## Development Setup

### Prerequisites

- Docker & Docker Compose  
- Git  

### Installation Steps

1. Clone the repository:
   ```bash
   git clone <repository-url>


