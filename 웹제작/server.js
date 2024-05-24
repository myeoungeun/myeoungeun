const express = require('express');
const app = express();
const path = require('path');
const mysql = require('mysql');
const cors = require('cors');
const WebSocket = require('ws');

app.use(cors());
app.use(express.static('public'));

// MySQL 연결 설정
const db = mysql.createConnection({
  host: 'azza.gwangju.ac.kr',
  user: 'dbuser211927',
  password: 'ce1234',
  database: 'db211927'
});

db.connect(err => {
  if (err) {
    console.error('MySQL 연결 오류:', err);
    return;
  }
  console.log('MySQL 연결 성공');
});

// 특정 날짜와 호실의 낙상 정보 가져오기
app.get('/api/falls', (req, res) => {
  const date = req.query.date;
  const room = req.query.room;

  const query = `
    SELECT F_TIME AS time, COUNT(*) AS fall_count
    FROM Fall
    WHERE F_DATE = ? AND F_ROOM = ?
    GROUP BY F_TIME
    ORDER BY F_TIME
  `;
  db.query(query, [date, room], (err, results) => {
    if (err) {
      console.error('쿼리 실행 오류:', err);
      res.status(500).send('서버 오류');
      return;
    }
    res.json(results);
  });
});

// HTTP 서버 설정
app.use("/", function(req, res) {
    res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(8080, () => {
    console.log('HTTP 서버가 http://localhost:8080 에서 실행 중입니다.');
});

// 웹소켓 서버 설정
const socketServer = new WebSocket.Server({ port: 22022 });

socketServer.on('connection', (ws, req) => {
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
