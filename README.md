# DIU Result Portal Documentation

---

## Table of Contents
1. **Introduction**
   - Overview of the DIU Result Portal
   - Purpose and Objectives
   - Target Audience
2. **System Overview**
   - Features of the Portal
   - Technology Stack Used
3. **System Requirements**
   - Hardware Specifications
   - Software Prerequisites
   - Network Requirements
4. **System Design**
   - Architecture Overview
   - Database Design (Tables, Relations)
   - API Design
5. **Codebase Overview**
   - Explanation of Key Files and Folders
   - Code Functionality Breakdown
6. **Installation Guide**
   - Prerequisites
   - Installation Steps
7. **User Manual**
   - Viewing Results
   - Downloading Results
8. **Security Features**
   - Data Encryption
   - Access Control
   - Secure Communication
9. **FAQs**
   - Common Issues and Resolutions
10. **Conclusion**
    - Future Scope and Enhancements
    - Acknowledgments

---

## 1. Introduction

### Overview of the DIU Result Portal
The DIU Result Portal is an integrated, web-based system that facilitates result management for students, faculty, and administrators of Daffodil International University. It simplifies result publication and retrieval, ensuring accuracy, security, and accessibility.

#### Features:
- Automated result processing.
- Secure, role-based access for different user types.
- Downloadable and printable result formats.

### Purpose and Objectives
- **Purpose**: To streamline the process of academic result management and make result access faster and more secure.
- **Objectives**: 
  - Reduce manual errors in managing academic records.
  - Provide a robust platform for data accessibility and transparency.
  - Empower administrators to oversee the system effectively.

### Target Audience
1. **Students**: Retrieve semester results and download them.

---

## 2. System Overview

### Features of the Portal
1. **Student Result**: 
   - View personal semester results.
   - Download results in PDF format.

### Technology Stack Used
1. **Frontend**: HTML, CSS, and JavaScript for a responsive interface.
2. **Backend**: PHP for server-side logic and API handling.
3. **Hosting**: Localhost for development; deployable to Vercel or any PHP-supported platform.

---

## 3. System Requirements

### Hardware Specifications
#### For the Server:
- **Minimum**: 
  - 2-core CPU, 8GB RAM, 100GB SSD Storage.
  - 4-core CPU, 8GB RAM, 100GB SSD Storage.
- **Recommended**: 
  - 4-core CPU, 16GB RAM, 200GB SSD Storage.

#### For Client Devices:
- Any modern PC, smartphone, or tablet with an updated browser.

### Software Prerequisites
- PHP version 7.4 or higher.
- MySQL version 5.7 or higher.
- Apache or Nginx web server.

---

## 4. System Design

### Architecture Overview
The portal employs a 2-tier architecture:
1. **Presentation Layer**: Frontend interface for interaction.
2. **Application Layer**: Backend server for logic and data handling.

### API Database Design
#### Key Tables
- **Students**: `student_id`, `name`, `program`.
- **Results**: `result_id`, `student_id`, `semester_id`, `course_id`, `grade`, `credit`.
- **Semesters**: `semester_id`, `name`, `year`.

#### Relationships:
1. **One-to-Many**: A student can have multiple results.
2. **Many-to-One**: Results belong to a specific semester.

---

## 5. Codebase Overview

### Key Files
1. `diuapi.php`: 
   - Handles API requests for retrieving student and result data.
2. `index.php`: 
   - Displays student results and computes SGPA.
3. `vercel.json`: 
   - Configuration file for deployment on Vercel.

### Code Functionality
1. **SGPA Calculation**: 
   - Computes SGPA dynamically based on course grades and credits.
2. **PDF Export**: 
   - Allows students to download printable results.

---

## 6. Installation Guide

### Prerequisites
- Install PHP, MySQL, and Apache/Nginx server.

### Installation Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/tahsin247/diuresult.git

### Navigate to the project directory:

cd diuresult
Start the server:
php -S localhost:8000



## 7. User Manual
Logging In
- Open the URL.
- Enter your ID and the selected semester option.
- Click Submit.
Viewing Results
- Navigate to the Results page.
- Select your semester from the dropdown.
- Click Submit.
## Downloading Results
- Click the Download PDF button to save your results locally.
## 8. Security Features
- Encryption
- Passwords are hashed using bcrypt.
- Data transmitted over HTTPS.
- Role-Based Access Control
Admin, Faculty, and Students have clearly defined access privileges.
## 9. FAQs
- How do I export results?
- Use the Print button on the results page.
- 10. Conclusion
- Future Scope
- Mobile application integration.
- Real-time notifications for result updates.
  

- ### Acknowledgments
1. info:
   ```bash
   ## DEVELOPED BY THE SWE 41 SOFT MAFIA.
   Email: hello@hamim.info
   
