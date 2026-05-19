const https = require('https');

const data = "user=newstew1&pass=Webmaker%400070%23";

const options = {
    hostname: 'newsthetruth.com',
    port: 2083,
    path: '/login/',
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': Buffer.byteLength(data)
    },
    rejectUnauthorized: false
};

const req = https.request(options, (res) => {
    let rawData = '';
    res.on('data', (chunk) => rawData += chunk);
    res.on('end', () => {
        if (res.statusCode === 301 || res.statusCode === 302 || res.statusCode === 303) {
            console.log("REDIRECT:", res.headers.location);
        } else {
            console.log("STATUS:", res.statusCode);
            const cpSessionMatch = rawData.match(/cpsess\d+/);
            if(cpSessionMatch) {
               console.log("FOUND SESSION:", cpSessionMatch[0]);
            } else {
               console.log("RESPONSE:", rawData.substring(0, 500));
            }
        }
    });
});

req.on('error', (e) => console.error(e));
req.write(data);
req.end();
