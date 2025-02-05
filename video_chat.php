<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$interest = isset($_GET['interest']) ? $_GET['interest'] : "General";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Chat | LonelyChat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/socket.io-client@4.4.1/dist/socket.io.js"></script>
    <style>
        body { background-color: #f8f9fa; text-align: center; }
        video { width: 45%; border-radius: 10px; margin: 10px; display: none; }
        #videos { display: flex; justify-content: center; align-items: center; flex-wrap: wrap; }
        #waitingScreen {
            position: fixed; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.8); color: white;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            font-size: 20px;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px; height: 50px;
            animation: spin 1s linear infinite;
            margin-top: 10px;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .btn-exit { margin-top: 20px; }
    </style>
</head>
<body>

    <h2>Video Chat - Interest: <?php echo htmlspecialchars($interest); ?></h2>
    
    <!-- Waiting Screen -->
    <div id="waitingScreen">
        <p>Looking for a match...</p>
        <div class="loader"></div>
    </div>

    <div id="videos">
        <video id="localVideo" autoplay playsinline muted></video>
        <video id="remoteVideo" autoplay playsinline></video>
    </div>

    <button class="btn btn-danger btn-exit" onclick="exitChat()">Exit Chat</button>

    <script>
        const interest = "<?php echo htmlspecialchars($interest); ?>";
        const username = "<?php echo $_SESSION['user']; ?>";
        const socket = io("http://localhost:3000");

        let localStream, peerConnection;
        const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };

        navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(stream => {
            localStream = stream;
            document.getElementById("localVideo").srcObject = stream;
            socket.emit("joinRoom", { interest, username });
        });

        socket.on("matchFound", user => {
            document.getElementById("waitingScreen").style.display = "none"; // Hide waiting screen
            document.getElementById("localVideo").style.display = "block";
            document.getElementById("remoteVideo").style.display = "block";
            startCall();
        });

        function startCall() {
            peerConnection = new RTCPeerConnection(config);
            localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

            peerConnection.onicecandidate = event => {
                if (event.candidate) {
                    socket.emit("iceCandidate", event.candidate);
                }
            };

            peerConnection.ontrack = event => {
                document.getElementById("remoteVideo").srcObject = event.streams[0];
            };

            peerConnection.createOffer().then(offer => {
                return peerConnection.setLocalDescription(offer);
            }).then(() => {
                socket.emit("offer", peerConnection.localDescription);
            });
        }

        socket.on("offer", offer => {
            peerConnection = new RTCPeerConnection(config);
            localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

            peerConnection.onicecandidate = event => {
                if (event.candidate) {
                    socket.emit("iceCandidate", event.candidate);
                }
            };

            peerConnection.ontrack = event => {
                document.getElementById("remoteVideo").srcObject = event.streams[0];
            };

            peerConnection.setRemoteDescription(new RTCSessionDescription(offer)).then(() => {
                return peerConnection.createAnswer();
            }).then(answer => {
                return peerConnection.setLocalDescription(answer);
            }).then(() => {
                socket.emit("answer", peerConnection.localDescription);
            });
        });

        socket.on("answer", answer => {
            peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
        });

        socket.on("iceCandidate", candidate => {
            peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
        });

        function exitChat() {
            socket.emit("leaveRoom", { interest, username });
            window.location.href = "dashboard.php";
        }
    </script>

</body>
</html>
