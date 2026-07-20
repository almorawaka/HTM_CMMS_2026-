https://bmes-cmms.infinityfree.me//login.php?logout=1  UN - admin, PW - admin123
# CMMS_2026
Intelligent IoT-Based Computerized Maintenance Management System (CMMS)

# Intelligent Medical Equipment CMMS
### IoT-Based Computerized Maintenance Management System (CMMS) with Predictive Maintenance

![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3.x-green)
![License](https://img.shields.io/badge/License-MIT-brightgreen)
![Status](https://img.shields.io/badge/Status-Active%20Development-success)

## Overview

The **Intelligent Medical Equipment CMMS** is a web-based Healthcare Technology Management (HTM) platform designed to improve the management, monitoring, maintenance, and lifecycle tracking of medical equipment in hospitals.

Unlike conventional CMMS solutions, this project integrates **IoT-based real-time monitoring**, **Equipment Health Assessment**, and **Predictive Maintenance** into a single platform to support biomedical engineering departments responsible for managing medical devices across multiple healthcare institutions.

The system aims to reduce equipment downtime, optimize preventive maintenance, improve asset utilization, and support data-driven maintenance decisions.

---

# Key Features

## Asset Management

- Medical equipment registration
- QR Code & Barcode support
- Equipment categorization
- Manufacturer management
- Department & Hospital management
- Equipment image management
- Equipment lifecycle tracking

---

## Maintenance Management

- Preventive Maintenance Scheduling
- Corrective Maintenance
- Work Order Management
- Maintenance History
- Service Records
- Breakdown Recording
- Downtime History
- Runtime History
- MTBF & MTTR Calculation

---

## Live Equipment Monitoring

- ESP32 IoT Integration
- MQTT Communication
- Real-Time Equipment Status
- Voltage Monitoring
- Current Monitoring
- Power Consumption
- Frequency Monitoring
- Temperature Monitoring
- Humidity Monitoring
- Equipment Online/Offline Detection

---

## Equipment Health Card

The Equipment Health Card provides a comprehensive overview of equipment condition, including:

- Equipment Information
- Live Sensor Data
- Runtime History
- Downtime History
- Breakdown History
- Equipment Health Score
- Reliability Score
- Utilization Score
- Risk Score
- Predictive Maintenance Status
- Power Consumption Trends
- MTBF
- MTTR
- Equipment Availability

---

## Predictive Maintenance

- Equipment Health Score
- Remaining Useful Life (RUL)
- Failure Probability
- Anomaly Detection
- Maintenance Priority
- Maintenance Recommendation
- Equipment Criticality Assessment

---

## Inventory Management

- Spare Parts Management
- Vendor Management
- Purchase History
- Stock Monitoring
- Low Stock Alerts
- Spare Part Usage History

---

## Dashboard & Analytics

- Equipment Dashboard
- Live Monitoring Dashboard
- KPI Dashboard
- Equipment Utilization Dashboard
- Preventive Maintenance Dashboard
- Equipment Reliability Dashboard
- Power Consumption Dashboard
- Hospital Performance Dashboard

---

# Technology Stack

## Backend

- PHP 8.2
- MySQL 8
- Apache (WAMP)

## Frontend

- HTML5
- CSS3
- Bootstrap 4
- AdminLTE 3
- JavaScript
- jQuery
- AJAX

## Charts & Visualization

- Chart.js
- DataTables

## IoT Technologies

- ESP32
- MQTT
- Node-RED
- JSON
- REST API

## Development Tools

- Visual Studio Code
- phpMyAdmin
- Git
- GitHub
- Postman

---

# Database Modules

Current database includes modules for:

- Users
- Roles
- Institutes
- Departments
- Equipment
- Equipment Categories
- Manufacturers
- Equipment Images
- PM Schedule
- PM History
- Work Orders
- Repair Requests
- Equipment Runtime History
- Equipment Downtime History
- Equipment Breakdowns
- Equipment Utilization
- Equipment Health Score
- Equipment Predictive Data
- Equipment Sensor Data
- Spare Parts
- Vendors
- Inventory Transactions
- Live Monitoring Equipment
- MQTT Configuration

---

# Project Architecture

```
                 +--------------------+
                 |   Web Dashboard    |
                 +--------------------+
                          |
                   PHP + MySQL
                          |
          +---------------+---------------+
          |                               |
    CMMS Database                  IoT Monitoring
          |                               |
  Maintenance Module               MQTT Broker
          |                               |
  Reports & Analytics              ESP32 Devices
          |                               |
          +---------------+---------------+
                          |
                  Medical Equipment
```

---

# Benefits

- Centralized management of medical equipment
- Multi-hospital asset management
- Reduced equipment downtime
- Improved preventive maintenance planning
- Better utilization of limited biomedical engineering staff
- Reduced unnecessary travel for maintenance personnel
- Faster fault detection
- Data-driven maintenance decisions
- Improved equipment availability
- Enhanced patient safety
- Lower maintenance costs

---

# Future Enhancements

## Artificial Intelligence

- AI-based fault diagnosis
- Machine Learning prediction models
- Remaining Useful Life estimation
- Explainable AI (XAI)
- Predictive spare parts forecasting

---

## Mobile Applications

- Android App
- iOS App
- Technician Mobile Dashboard
- QR Scanner
- Offline Synchronization

---

## Smart Hospital Integration

- Hospital Information System (HIS)
- Laboratory Information System (LIS)
- Radiology Information System (RIS)
- PACS Integration
- HL7 Integration
- FHIR API
- DICOM Connectivity

---

## Advanced IoT

- Digital Twin
- Edge Computing
- Cloud Deployment
- Remote Firmware Update
- Energy Analytics
- Environmental Monitoring
- Battery Health Monitoring

---

## Enterprise Features

- Multi-Hospital Management
- Regional Biomedical Engineering Centers
- National Equipment Monitoring Dashboard
- Role-Based Workflow
- Multi-Language Support
- Audit Logs
- Cybersecurity Enhancements

---

# Research Objectives

This project aims to develop an intelligent Healthcare Technology Management platform capable of:

- Improving maintenance efficiency
- Supporting predictive maintenance
- Monitoring medical equipment in real time
- Reducing unnecessary maintenance visits
- Optimizing limited biomedical engineering resources
- Supporting centralized management of geographically distributed hospitals
- Enhancing equipment reliability and patient safety

---

# Current Development Status

### Completed

- User Management
- Equipment Management
- Preventive Maintenance
- Equipment Images
- Equipment Runtime History
- Equipment Downtime History
- Equipment Breakdown History
- Equipment Health Card
- Live Monitoring Dashboard
- Equipment Health Score
- Predictive Maintenance Module
- Reliability KPIs
- Inventory Management
- QR Code Support

### In Progress

- MQTT Live Data Integration
- ESP32 Sensor Firmware
- Predictive Analytics Engine
- Mobile Application
- AI-Based Health Score Optimization

### Planned

- National Deployment
- Cloud Platform
- Digital Twin
- Explainable AI
- Mobile Technician App
- Automated Maintenance Scheduling
- Multi-Tenant Architecture

---

# Project Vision

To develop a next-generation Intelligent Healthcare Technology Management Platform that enables biomedical engineering departments to remotely monitor, maintain, and manage medical equipment across multiple hospitals using IoT, Artificial Intelligence, and Predictive Maintenance technologies.

---

# Author

**M. A. L. Morawaka**

Biomedical Technological Officer

Division of Biomedical Engineering Services

Ministry of Health

Sri Lanka

---

# License

This project is released under the **MIT License**.

---

# Contributing

Contributions, suggestions, bug reports, and feature requests are welcome.

If you would like to contribute:

1. Fork the repository
2. Create a new feature branch
3. Commit your changes
4. Push to your branch
5. Submit a Pull Request

---

# Acknowledgements

- Ministry of Health, Sri Lanka
- Division of Biomedical Engineering Services
- Eulji University
- Lee Jong-Wook Fellowship Program
- Open Source Community

---

## Expected Milestones

---
Year 1
- Complete CMMS software v
- Central equipment database v
- Preventive maintenance scheduling v
- Repair and work-order management v
- Inventory and spare-parts management
- Reporting dashboard
- User management v
---
Year 2
- Design MEMM hardware
- ESP32 sensor modules
- MQTT server
- Live Monitoring Dashboard
- Equipment Health Card
 Runtime/Downtime analytics
- Equipment utilization monitoring
- Real-time notifications
---
Year 3
- Predictive maintenance engine
- Health score calculation
- Remaining Useful Life (RUL)
- AI failure prediction
- Mobile application
- Enterprise deployment
- National equipment analytics
- Performance evaluation and publications
