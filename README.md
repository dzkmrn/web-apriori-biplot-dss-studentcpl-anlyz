# Web Apriori Biplot DSS - Student CPL Analysis

A comprehensive Decision Support System (DSS) built with Laravel for analyzing student Capaian Pembelajaran Lulusan (CPL) data using the Apriori algorithm and generating association rules for graduate profile recommendations.

## 📋 Project Overview

This web application is designed to help educational institutions analyze student learning outcomes (CPL) and identify patterns that can inform curriculum development and student guidance. The system uses association rule mining with the Apriori algorithm to discover relationships between different CPL achievements and recommend suitable graduate profiles.

## ✨ Key Features

- **Student Data Management**: Import and manage student data with CPL values
- **CPL Master Data**: Comprehensive management of Capaian Pembelajaran Lulusan
- **Apriori Algorithm**: Advanced association rule mining for pattern discovery
- **Graduate Profile Recommendations**: Automated recommendations for student career paths
- **Data Visualization**: Interactive charts and dashboards for data analysis
- **Multi-rule Analysis**: Support for 1-to-1, 2-to-1, and 3-to-1 association rules
- **Excel Import/Export**: Seamless data handling with spreadsheet integration

## 🎯 Graduate Profiles Supported

The system analyzes CPL data to recommend one of three graduate profiles:

1. **Studi Lanjut** (Further Studies) - Focus on academic continuation
2. **Pegawai Profesional** (Professional Employee) - Corporate career path
3. **Kewirausahaan** (Entrepreneurship) - Business and startup orientation

## 🛠️ Technology Stack

### Backend
- **PHP 8.2+**
- **Laravel 12.x**
- **MySQL/MariaDB**
- **Apriori Algorithm Implementation**

### Frontend
- **Tailwind CSS 4.x**
- **Vite**
- **Chart.js Integration**
- **Bootstrap Components**

### Key Dependencies
- `maatwebsite/excel` - Excel import/export functionality
- `phpoffice/phpspreadsheet` - Spreadsheet processing
- `nesbot/carbon` - Date and time handling

## 📦 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL/MariaDB database

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd web-apriori-biplot-dss-studentcpl-anlyz
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install JavaScript Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Generate Application Key
```bash
php artisan key:generate
```

### Step 6: Run Database Migrations
```bash
php artisan migrate
```

### Step 7: Seed Initial Data (Optional)
```bash
php artisan db:seed
```

### Step 8: Build Frontend Assets
```bash
npm run build
```

### Step 9: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🚀 Usage Guide

### 1. Setting Up Master Data
- Navigate to **CPL Management** to define your Capaian Pembelajaran Lulusan
- Configure CPL categories and codes according to your curriculum
- Set up **Profil Lulusan** definitions for the three graduate profiles

### 2. Importing Student Data
- Use the **Data Mahasiswa** section to import student data
- Supported formats: Excel (.xlsx, .xls)
- Required fields: NIM, Name, Angkatan (batch year), CPL values

### 3. Running Apriori Analysis
- Go to **Analisis** section
- Configure parameters:
  - **Min Support**: Minimum support threshold (0.01 - 1.0)
  - **Min Confidence**: Minimum confidence threshold (0.01 - 1.0)
  - **Angkatan**: Select student batch to analyze
- Run analysis to generate association rules

### 4. Interpreting Results
- View generated association rules with support and confidence values
- Analyze graduate profile recommendations
- Export results for further analysis

## 📊 Data Structure

### Database Tables
- `users` - System administrators
- `mahasiswas` - Student data with CPL values
- `cpls` - Master CPL definitions
- `profil_lulusans` - Graduate profile definitions
- `association_rules` - Generated association rules
- `data_histori` - Analysis history and results

### CPL Categories
The system supports 6 CPL categories:
1. Penguasaan dan penerapan ilmu dasar sains dan matematik
2. Kemampuan perumusan solusi permasalahan pada objek Teknik Industri
3. Kemampuan perancangan dan penelitian pada objek sistem integrasi
4. Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri
5. Penguasaan aspek non-akademis pendukung
6. Penguasaan keilmuaan pendukung kewirausahaan

## 🔧 Configuration

### Apriori Algorithm Parameters
Edit the analysis parameters in the AnalisisController:
- Minimum support threshold
- Minimum confidence threshold
- Rule generation types (1-to-1, 2-to-1, 3-to-1)

### Excel Import Configuration
Configure import templates in `config/excel.php`

## 🧪 Testing

Run the test suite with:
```bash
php artisan test
```

## 📈 Performance Considerations

- For large datasets (>1000 students), consider running analysis during off-peak hours
- The Apriori algorithm complexity increases with the number of items and transactions
- Optimal min_support and min_confidence values should be determined through experimentation

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions, please contact the development team or create an issue in the repository.

## 🔄 Version History

- **v1.0.0** - Initial release with basic Apriori implementation
- **v1.1.0** - Added multi-rule analysis and graduate profile recommendations
- **v1.2.0** - Enhanced data visualization and Excel import/export

---

Built with ❤️ using Laravel and modern web technologies.