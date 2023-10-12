# Treasury Tracker

Treasury Tracker is designed to monitor Polkadot governance referenda. Built on CakePHP 4.x, it aims to streamline and organize Polkadot governance proposals for ease of access and analysis.

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Usage](#usage)
- [Testing Guide](#testing-guide)
- [Configuration](#configuration)
- [Layout](#layout)
- [Updating](#updating)
- [Contribution](#contribution)
- [License](#license)

## Introduction

The Treasury Tracker provides a structured view of governance proposals on Polkadot, making it easier for stakeholders to review, analyze, and take action on the latest referenda.

## Features

- **Governance Referenda Tracking:** Stay updated with the latest governance proposals and their details.
- **Analytics Tracking:** Gain insights into proposal trends, voting patterns, and stakeholder participation over time. With integrated analytics, you can visualize the impact of each proposal, understand community sentiment, and predict potential outcomes. This feature allows for informed decisions and strategies, ensuring your actions are data-driven and effective.
- **PolkadotJS API Integration:** Fetch real-time data directly from the Polkadot network using our built-in [PolkadotJS Proxy](https://github.com/stake-plus/polkadotjs-proxy).
- **Easy Configuration:** Customize your deployment with `app_local.php` configurations.

## Usage

To get started with the Treasury Tracker:

1. Clone the repository.
2. Install dependencies using `composer install`.
3. Configure your environment in `app_local.php`.
4. Navigate to the root directory and run `bin/cake server` to start the local server.
5. Open a web browser and navigate to `http://localhost:8765` to view the tracker.

## Testing Guide

### Pre-requisites

- [Docker](https://www.docker.com/) installed and running.

### Steps:

1. **Clone the Repository:** If you haven't already, clone the Treasury Tracker repository.
2. **Build Docker Containers:** Navigate to the project's root directory and run `docker-compose up -d`. This will create and start the required containers.
3. **Access the Application:** In your browser, navigate to `http://localhost:9000` (or the port you've set in your docker-compose file) to access the application.
4. **Access CLI:** Login to the project's web docker container `docker exec -it docker-test_web_1 /bin/bash`.
5. **Run Tests:** Navigate to the project's root directory and run the test suite with `./vendor/bin/phpunit -v tests`.

## Configuration

To configure the application, read and edit the environment-specific `config/app_local.php` and set up the 'Datasources' and any other configuration relevant to your application. 

## Layout

The app skeleton uses the Milligram (v1.3) minimalist CSS framework by default. However, this can be replaced with any other library or custom styles.

## Updating

Since Treasury Tracker serves as a customized solution for Polkadot governance, updates have to be done manually as there isn't a method to provide automated upgrades.

## Contribution

Information on contributing to the Treasury Tracker will be provided soon.

## License

This project is licensed under the Apache 2.0 License - see the [LICENSE](LICENSE) file for details.
