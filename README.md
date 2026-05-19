# 🌾 AgriPool

A comprehensive digital marketplace platform connecting farmers, transporters, and agricultural cooperatives to streamline agricultural commerce, logistics, and market access across regions.

---

## 📋 Table of Contents

- [Features](#features)
- [System Architecture](#system-architecture)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Key Modules](#key-modules)
- [Contributing](#contributing)
- [License](#license)

---

## ✨ Features

### User Management

- **Role-Based Access Control**: Farmers, Transporters, Agents, and Admins
- **KYC Verification**: Know Your Customer verification for user authentication
- **Profile Management**: Detailed user profiles with credentials and verification status

### Booking & Transportation

- **Transport Listings**: Transporters can post available transport capacity
- **Booking System**: Farmers request transportation, transporters accept/decline
- **Load Board**: Real-time visibility of available loads and transport requests
- **Transport Matching**: Intelligent matching algorithm for optimal ride/load pairing
- **Fare Calculator**: Dynamic fare calculation based on distance and route

### Market Intelligence

- **Market Prices**: Real-time commodity pricing by region
- **Price Alerts**: Automatic notifications for price changes
- **Market Analysis**: Trend analysis and market insights
- **Cooperative Groups**: Collective market access for farmers

### Communication & Notifications

- **Messaging System**: Direct messaging between users
- **In-App Notifications**: Real-time alerts for bookings, prices, and updates
- **Email Notifications**: KYC status, booking confirmations, price alerts

### Dispute Resolution

- **Dispute Management**: Handle conflicts between parties
- **Review System**: Rate and review other users
- **Activity Logging**: Complete audit trail of transactions

### Payment & Wallet

- **Digital Wallet**: Secure wallet management
- **Transaction Tracking**: Detailed transaction history
- **Payment Integration**: Support for multiple payment methods

### Additional Features

- **Multi-Language Support**: Hindi and English localization
- **Help/Support System**: Ticketing system for user support
- **Leaderboard**: Top performing farmers and transporters
- **Tracking**: Real-time shipment tracking
- **Export Functionality**: Export data in multiple formats

---

## 🏗️ System Architecture

```
AgriPool (Laravel 11)
├── Frontend (Vue.js/Tailwind CSS)
├── Backend (Laravel API)
├── Database (MySQL/PostgreSQL)
└── Services & Jobs (Queue Processing)
```

### Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Vue.js, Tailwind CSS, Vite
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Queue**: Laravel Queue with support for multiple drivers
- **Mail**: Laravel Mail with configurable drivers

---

## 📦 Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 16.0 or higher
- **npm**: 8.0 or higher
- **MySQL/PostgreSQL**: 5.7+ or 10+
- **Git**: For version control

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Rav790/AgriPool.git
cd AgriPool
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

---

## ⚙️ Configuration

### 1. Update `.env` File

```env
APP_NAME=AgriPool
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agripool
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
MAIL_FROM_ADDRESS=noreply@agripool.com

QUEUE_CONNECTION=database
```

### 2. Configure Database Connection

Update database credentials in `.env` based on your setup (MySQL or PostgreSQL)

### 3. Configure Mail Service

Set up a mail driver (Mailtrap, SendGrid, Mailgun, etc.) for sending notifications

---

## 🗄️ Database Setup

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Database (Optional)

```bash
php artisan db:seed
```

### 3. Create Storage Link

```bash
php artisan storage:link
```

---

## ▶️ Running the Application

### Development Environment

#### Terminal 1: Start PHP Development Server

```bash
php artisan serve
```

Application runs at `http://localhost:8000`

#### Terminal 2: Start Vite Development Server

```bash
npm run dev
```

Frontend dev server runs at `http://localhost:5173`

#### Terminal 3: Start Queue Worker (Optional)

```bash
php artisan queue:work
```

### Production Build

```bash
npm run build
php artisan optimize
```

---

## 📁 Project Structure

```
AgriPool/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # API and web controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form validation requests
│   ├── Models/                 # Database models
│   ├── Notifications/          # Notification classes
│   ├── Services/               # Business logic services
│   └── Providers/              # Service providers
├── bootstrap/                  # Application bootstrap files
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── factories/              # Model factories
├── public/                     # Publicly accessible files
├── resources/
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript components
│   └── views/                  # Blade templates
├── routes/                     # Route definitions
├── storage/                    # Application storage
├── tests/                      # Test files
├── vendor/                     # Composer dependencies
├── composer.json               # PHP dependencies
├── package.json                # NPM dependencies
└── vite.config.js             # Vite configuration
```

---

## 🔑 Key Modules

### Models

- **User**: Core user entity with roles
- **Booking**: Transportation booking records
- **TransportListing**: Available transport capacity
- **TransportRequest**: Farmer transport requests
- **Market**: Market listings and information
- **MarketPrice**: Commodity pricing data
- **Dispute**: Conflict resolution records
- **Review**: User ratings and reviews
- **Wallet**: User wallet management
- **Message**: User-to-user communication
- **PriceAlert**: Automated price notifications
- **CooperativeGroup**: Group management

### Services

- **TransportMatchingService**: Intelligent matching algorithm for transport requests

### Controllers

- **Admin**: Administrative dashboard and management
- **Farmer**: Farmer-specific features
- **Transporter**: Transporter-specific features
- **Agent**: Agent dashboard
- **Booking**: Booking management
- **Market**: Market data and pricing
- **KYC**: User verification process

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 📧 Contact & Support

For support, questions, or feedback, please create an issue in the repository or contact the development team.

---

**Built with ❤️ for the agricultural community**
