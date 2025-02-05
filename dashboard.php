<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Show popup only once per session
if (!isset($_SESSION["popup_shown"])) {
    $_SESSION["popup_shown"] = true;
    $showPopup = true;
} else {
    $showPopup = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LonelyChat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background: #343a40;
            color: white;
            position: fixed;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .interest-card {
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
            text-align: center;
        }
        .interest-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .popup {
            display: <?php echo $showPopup ? 'block' : 'none'; ?>;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 9999;
            border-radius: 8px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="sidebar">
        <div>
            <h4 class="text-center">LonelyChat</h4>
            <a href="#">Profile</a>
            <a href="#">Friends</a>
        </div>
        <div>
            <a href="logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Welcome, <?php echo $_SESSION["user"]; ?>!</h2>
        <h4>Select an Interest</h4>
        <div class="row mt-4">
            <?php
            $interests = ["Movies", "Politics", "Travel", "Sports", "Relationship"];
            $colors = ["primary", "success", "danger", "warning", "info"];
            $icons = ["🎬", "🏛", "✈️", "⚽", "❤️"];

            foreach ($interests as $index => $interest) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card interest-card border-' . $colors[$index] . '" onclick="selectInterest(\'' . $interest . '\')">
                            <div class="card-body">
                                <h2>' . $icons[$index] . '</h2>
                                <h5 class="card-title">' . $interest . '</h5>
                            </div>
                        </div>
                    </div>';
            }
            ?>
        </div>

        <!-- Connect Button -->
        <div id="connectSection" class="mt-3 hidden">
            <button id="connectBtn" class="btn btn-primary">Connect</button>
        </div>
    </div>

    <!-- Popup Message -->
    <div id="popupMessage" class="popup">
        <p><strong>"Being alone is more painful than getting hurt."</strong></p>
        <p>- Luffy</p>
        <button class="btn btn-secondary" onclick="closePopup()">Continue</button>
    </div>

    <script>
        let selectedInterest = "";

        function closePopup() {
            document.getElementById("popupMessage").style.display = "none";
        }

        function selectInterest(interest) {
            selectedInterest = interest;
            document.getElementById("connectSection").classList.remove("hidden");
        }

        document.getElementById("connectBtn").addEventListener("click", function () {
            if (selectedInterest) {
                window.location.href = "video_chat.php?interest=" + encodeURIComponent(selectedInterest);
            }
        });

        // Ensure the Continue button properly hides the popup
        document.querySelector(".btn-secondary").addEventListener("click", closePopup);
    </script>

</body>
</html>
