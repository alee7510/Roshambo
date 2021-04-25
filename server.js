'use strict';

const express = require('express');
const { Server } = require('ws');

const PORT = process.env.PORT || 3000;
const INDEX = '/index.html';

const server = express()
  .use((req, res) => res.sendFile(INDEX, { root: __dirname }))
  .listen(PORT, () => console.log(`Listening on ${PORT}`));

const wss = new Server({ server });

wss.on('connection', ws => {
  console.log('connection opened');
  ws.send('Hello from Node.js server');

  ws.on('message', msg => {
    console.log('message: ' + msg);
  });

  ws.on('close', () => {
    console.log('connection closed');
  });
});

setInterval(() => {
  wss.clients.forEach((client) => {
    client.send(new Date().toTimeString() + " this is the time");
  });
}, 1000);
