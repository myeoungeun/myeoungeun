const express = require('express');
const app = express();
const path = require('path');

app.use("/", function(req, res) {
    res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(8080, () => {
    console.log('HTTP 서버가 http://localhost:8080 에서 실행 중입니다.');
});

const WebSocket = require('ws');

const socket = new WebSocket.Server({ port: 8081 });

socket.on('connection', (ws, req) => {
    console.log('새로운 웹소켓 연결이 설정되었습니다.');

    ws.on('message', (msg) => {
        // msg가 Blob인 경우, 이를 문자열로 변환합니다.
        if (msg instanceof Buffer) {
            msg = msg.toString();  // Buffer를 문자열로 변환
        } else if (msg instanceof ArrayBuffer) {
            msg = Buffer.from(msg).toString();  // ArrayBuffer를 문자열로 변환
        }
        console.log('유저가 보낸 거: ' + msg);
        ws.send(msg);
    });
});
