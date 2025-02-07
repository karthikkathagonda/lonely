
**Introduction**

This is a PHP & MySQL-based Lonely Chat Website that allows users to connect with strangers based on shared interests via WebRTC video chat.

**Features**

User Authentication (Register, Login, Logout)

User Dashboard with interest selection

Matchmaking System for WebRTC video chat

Friends System (Add & manage friends)

UI/UX Enhancements (CSS & animations)

Secure and Optimized Code

**Folder Structure**

/your_project_folder
|-- db.php               # Database connection
|-- register.php         # User Registration
|-- login.php            # User Login
|-- logout.php           # User Logout
|-- dashboard.php        # User Dashboard
|-- video_chat.php       # WebRTC Video Chat
|-- assets/              # CSS, JS, Images
|-- README.md            # Project Documentation

**Installation Guide**

1. Clone the Repository

git clone https://github.com/yourusername/lonely-chat.git
cd lonely-chat

2. Setup the Database

Open phpMyAdmin and execute the SQL script (database.sql provided).

Update db.php with your database credentials.

3. Configure Web Server

Use Apache with PHP & MySQL.

Ensure mod_rewrite is enabled for friendly URLs.

4. Run the Application

Start the server and visit http://localhost/lonely-chat/.

Register a new user and explore the platform.

**Technologies Used**

PHP & MySQL (Backend & Database)

HTML, CSS, JavaScript (Frontend UI)

WebRTC (Video Chat System)

AJAX & jQuery (Dynamic Interactions)

**Contribution**

Feel free to fork, modify, and contribute to the project.
