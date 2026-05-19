const https = require('https');
const data = JSON.stringify({ email: 'test', password: 'test' });
const options = {
  hostname: 'newsthetruth.com',
  port: 443,
  path: '/api/auth/login',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': Buffer.byteLength(data),
    'Accept': 'application/json'
  }
};
const req = https.request(options, res => {
  console.log('STATUS:', res.statusCode);
  res.on('data', d => process.stdout.write(d));
});
req.on('error', e => console.error(e));
req.write(data);
req.end();
