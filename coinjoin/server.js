const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
  cors: {
    origin: "http://localhost:3000",
    methods: ["GET", "POST"],
    credentials: true
  }
});

app.get('/', (req, res) => {
  //res.sendFile(__dirname + '/index.html');
  res.send("Hello and welcome to my chat...")
});

io.on('connection', (socket) => {
  console.log('a user connected');
  io.emit("Welcome New User " + socket.id + " Joined")

  socket.on("disconnect", (reason) => {
    // ...
    console.log("Reason:", reason)
  });

});

io.on('connection', (socket) => {
  socket.on('chat message', (msg) => {
    console.log(msg)
    io.emit('chat message', msg);
  });
});

server.listen(9999, () => {
  console.log('listening on *:9999');
});


