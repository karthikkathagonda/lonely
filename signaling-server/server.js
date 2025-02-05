const io = require("socket.io")(3000, {
    cors: { origin: "*" }
});

console.log("✅ Signaling server started on port 3000");

io.on("connection", (socket) => {
    console.log("🔗 New user connected: " + socket.id);

    socket.on("joinRoom", ({ interest, username }) => {
        socket.join(interest);
        console.log(`👤 ${username} joined room: ${interest}`);

        const clients = io.sockets.adapter.rooms.get(interest);
        if (clients.size === 2) {
            console.log("🎥 Match found! Notifying users...");
            io.to(interest).emit("matchFound", username);
        }
    });

    socket.on("offer", (offer) => {
        socket.broadcast.emit("offer", offer);
    });

    socket.on("answer", (answer) => {
        socket.broadcast.emit("answer", answer);
    });

    socket.on("iceCandidate", (candidate) => {
        socket.broadcast.emit("iceCandidate", candidate);
    });

    socket.on("disconnect", () => {
        console.log("❌ User disconnected: " + socket.id);
    });
});
