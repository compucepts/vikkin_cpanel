const express = require('express')
const app = express()
const port = 8080
var morgan = require('morgan')
var fs = require('fs')
var path = require('path')
app.use(express.json());
var accessLogStream = fs.createWriteStream(path.join(__dirname, 'access.log'), {
    flags: 'a'
})
app.use(morgan('combined', {
    stream: accessLogStream
}))
var request = require('request');
var mysql = require('mysql');
var cron = require('node-cron');
var connection = mysql.createConnection({
    host: 'localhost',
    user: 'vikkin_wallet_vikkin',
    password: 'wallet_vikkin',
    database: 'vikkin__3107'
});
connection.connect();
connection.query('   SELECT @@global.time_zone, @@session.time_zone ,NOW()', function(err, rss) {
    if (err) throw err;
    console.log(rss);
});
connection.query(' SELECT  now() ', function(err, rss) {
    if (err) throw err;
    console.log(rss);
});

function updateCompleteTransaction(transactionId) {
    return new Promise(resolve => {
        resolve(response);
    });
}

function callWebHook(transactionId) {
    return new Promise(resolve => {
        var opti = {
            'method': 'GET',
            'url': 'https://api.vikkin.ltd/api/v3/webhook?transaction_id=' + transactionId,
            'headers': {}
        };
        request(opti, function(error, response) {
            if (error) throw new Error(error);
            resolve(response);
        });
    });
}

function sendNotificationToCustomer(row) {
    return new Promise(resolve => {
        var url = row.notification_url + '?transaction_id=' + row.transactionId;
        console.log(url);
        var opti = {
            'method': 'GET',
            'url': url,
            'headers': {}
        };
        request(opti, function(error, response) {
            if (error) throw new Error(error);
            resolve(response);
        });
    });
}

function getQR(data) {
    return new Promise(resolve => {
        var request = require('request');
        var options = {
            'method': 'POST',
            'url': 'https://pay.richpay.io/api/invoice/create/pix',
            'headers': {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "amount": data.amount,
                "api_key": "acded95a-eb3d-fd5d-f764-8e9a08088ff9",
                "document_number": "05418082736",
                "email": "pix.exchanges@gmail.com",
                "document_type": "cpf",
                "name": "name",
                "description": data.description,
                "postback_url": 'https://api.vikkin.ltd/webhook.php?api_key=' + data.api_key
            })
        };
        console.log(options);
        request(options, function(error, response) {
            if (error) throw new Error(error);
            console.log("response.body ");
            console.log(response.body);
            resolve(response.body);
        });
    });
}

function getBalance(user_id) {
    return new Promise(resolve => {
        var coin = "BRL";
        connection.query("SELECT BRL  FROM users where id=" + user_id + " ; ", function(error, results, fields) {
            if (error) throw error;
            var balance = results[0].BRL;
            resolve(balance);
        });
    });
}

function getInsertID(query) {
    return new Promise(resolve => {
        connection.query(query, function(error, results, fields) {
            if (error) reject(error);
            resolve(results.insertId);
        });
    });
}

function getUserID(api_key, email) {
    return new Promise(resolve => {
        connection.query("SELECT id  FROM users where  email='" + email + "'   ; ", function(error, results, fields) {
            if (error) reject(error);
            console.log(results);
            resolve(results[0].id);
        });
    }, reject => {
        reject("issue");
    });
}

function resolveAfter2Seconds2() {
    return new Promise(resolve => {
        setTimeout(() => {
            resolve('resolved2');
        }, 4000);
    });
}
app.post("/Status", (req, res) => {
    getTransaction(req.body).then((TransactionStatus) => {
        res.json(TransactionStatus);
    });
});
app.post("/qrcode", (req, res) => {
    getQRcode(req.body).then((qrcode) => {
        res.json(qrcode);
    });
});
app.get("/getUserID", (req, res) => {
    getUserID(req.body.api_key, req.body.email).then((re) => {
        console.log(re);
        res.json(re);
    });
});
app.get("/", (req, res) => {
    res.send("LIVE...");
});
async function getTransaction(reqJson) {
    console.log(reqJson);
    var user_id = await getUserID(reqJson.api_key, reqJson.email);
    if (user_id < 1) {
        console.log("Incorrect Login");
        return "Incorrect Login";
    }
    return new Promise(resolve => {
        var url = 'https://pay.richpay.io/api/invoice/check/' + reqJson.transactionId;
        console.log(url);
        var options = {
            'method': 'GET',
            'url': url,
            'headers': {
                'Content-Type': 'application/json'
            }
        };
        request(options, function(error, response) {
            if (error) throw new Error(error);
            dep = JSON.parse(response.body);
            var responsv3 = Object();
            responsv3 = {
                "status": dep.status,
                "transactionId": dep.token,
                "status_id": dep.invoice.status_id,
                "paid_with": dep.invoice.paid_with,
                "paid_at": dep.invoice.paid_at
            };
            console.log(responsv3);
            resolve(responsv3);
        });
    });
}
async function getQRcode(reqJson) {
    console.log(reqJson);
    var user_id = await getUserID(reqJson.api_key, reqJson.email);
    if (user_id < 1) {
        console.log("Incorrect Login");
        return "Incorrect Login";
    }
    var query = "insert into deposit_address (user_id,status,coin,amount,notification_url,request_dump) values (" + user_id + ",'NEW','BRL', " + reqJson.amount + ",'" + reqJson.notification_url + "','" + JSON.stringify(reqJson) + "')";
    console.log(query);
    var insertID = await getInsertID(query);
    console.log("insert idinsertId ");
    console.log(insertID);
    var depositRes = await getQR(reqJson);
    console.log(depositRes);
    var query = "update deposit_address  set dump ='" + depositRes + "' where id ='" + insertID + "'";
    var insert2 = await connection.query(query);
    dep = JSON.parse(depositRes);
    if (dep.status) {
        transactionsID = dep.data.token;
        qrcode_image = dep.data.qrcode_image;
        qrcode_value = dep.data.qrcode_value;
        var query = "update deposit_address  set transactionId ='" + transactionsID + "' ,  qrcode_image='" + qrcode_image + "',qrcode_value='" + qrcode_value + "' ,  dump ='" + depositRes + "' where id ='" + insertID + "'";
        console.log(query);
        var insert = await connection.query(query);
        var responsv3 = Object();
        responsv3 = {
            id: transactionsID,
            amount: reqJson.amount,
            payment_link: "https://link.vikkin.ltd/" + transactionsID,
            status: 'NEW',
            metadata: {
                qrcode: qrcode_image,
                brcode: qrcode_value
            },
        };
        console.log(responsv3);
        return responsv3;
    } else {
        return dep;
    }
}
app.listen(port, () => {
    console.log(`App listening on port ${port}`)
})
