# PHP E2E Testing Framework

## Table of Contents

1. [Introduction](#introduction)
2. [Features](#features)
3. [Requirements](#requirements)
4. [Setup](#setup)
5. [Project Structure](#project-structure)
6. [Running Tests](#running-tests)
7. [Generating Reports](#generating-reports)
8. [Contributing](#contributing)

## Introduction

This PHP-based End-to-End (E2E) testing framework leverages PHPUnit, Selenium WebDriver, and Allure for comprehensive UI test automation and reporting. It is designed for high reliability, extensibility, and ease of use.

## Features

- **WebDriver Integration:** Supports Selenium WebDriver for browser automation.
- **Parallel Test Execution:** Utilizes Paratest for parallel test execution.
- **Allure Reporting:** Generates detailed test reports with screenshots and videos for failed tests.
- **Reusable Components:** Implements Page Object Model (POM) and reusable components for maintainability.
- **Logging:** Comprehensive test logging with Monolog.

## Requirements

- **PHP:** ^8.1
- **Composer** (dependency manager for PHP)
- **Java Runtime Environment (JRE):** Required for Selenium Server.
- **Selenium Server Standalone:** v3.5.3
- **ChromeDriver:** Compatible with the installed Chrome version.

### Dependencies

#### Required

```json
"php-webdriver/webdriver": "^1.15",
"monolog/monolog": "^3.8"
```

#### Development

```json
"phpunit/phpunit": "^10",
"brianium/paratest": "^7",
"allure-framework/allure-phpunit": "^3.0"
```

## Setup

1. **Clone the Repository:**
   ```bash
   git clone <repository_url>
   cd <repository_name>
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   ```

3. **Configure Selenium Server:**
   - Place `selenium-server-standalone-3.5.3.jar` in the `tools` directory.
   - Start the server:
     ```bash
     java -jar tools/selenium-server-standalone-3.5.3.jar
     ```

4. **Set Up Allure:**
   - Install Allure Command Line Tool ([Installation Guide](https://docs.qameta.io/allure/#_installing_a_commandline)).
   - Configure `phpunit.xml` to use Allure.

5. **Set Up ChromeDriver:**
   - Ensure `chromedriver` is in your system PATH or provide its path during Selenium configuration.

6. **Set Up Allure Configuration:**
   - Add `config/allure.config.php` with the following content:
     ```php
     <?php
     return [
         'outputDirectory' => 'build/allure-results',
     ];
     ```

## Project Structure

```plaintext
.
├── build
│   ├── allure-report          # Allure report output
│   ├── allure-results         # Raw test results (screenshots, videos, JSON reports)
├── composer.json              # Dependency manager configuration
├── composer.lock              # Locked dependencies
├── logs
│   └── test.log               # Execution logs
├── phpunit.xml                # PHPUnit configuration
├── reports
│   └── test-results.xml       # JUnit XML test results
├── tests
│   ├── e2e
│   │   ├── AddJobTest.php     # Test case for adding jobs
│   │   ├── LoginTest.php      # Test case for login
│   │   ├── TestBase.php       # Base test class
│   │   ├── components         # Reusable UI components
│   │   ├── config             # Configuration files
│   │   ├── data               # Test data (e.g., credentials)
│   │   ├── pages              # Page Object classes
│   │   └── utils              # Utilities (e.g., logging, waits)
└── tools
    └── selenium-server-standalone-3.5.3.jar
```

## Running Tests

1. **Run All Tests:**
   ```bash
   ./vendor/bin/phpunit --testsuite "UI Tests"
   ```

2. **Run Tests in Parallel:**
   ```bash
   ./vendor/bin/paratest --processes=4 --testsuite "UI Tests"
   ```

3. **Run Specific Test File:**
   ```bash
   ./vendor/bin/phpunit tests/e2e/AddJobTest.php
   ```

## Generating Reports

1. **Run Tests and Generate Allure Results:**
   ```bash
   ./vendor/bin/phpunit --testsuite "UI Tests"
   ```

2. **Generate Allure HTML Report:**
   ```bash
   allure generate build/allure-results -o build/allure-report
   ```

3. **Serve Allure Report Locally:**
   ```bash
   allure serve build/allure-results
   ```

## Contributing

1. Fork the repository.
2. Create a feature branch.
3. Commit your changes.
4. Push the branch and create a pull request.

For any questions or issues, feel free to open an issue or contact the maintainers.

