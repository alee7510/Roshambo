'use strict';

const express = require('express');
const { Server } = require('ws');

const PORT = process.env.PORT || 80;
const INDEX = '/index.html';

const server = express()
  .use((req, res) => res.sendFile(INDEX, { root: __dirname }))
  .listen(PORT, () => console.log(`Listening on ${PORT}`));

const wss = new Server({ server });

wss.on('connection', ws => {
  console.log('connection opened');

  ws.on('message', msg => {
    console.log('message: ' + msg.trim());
    wss.clients.forEach((client) => {
      if (client !== ws) {
        client.send(data);
      }
    });
  });

  ws.on('close', () => {
    console.log('connection closed');
  });
});
