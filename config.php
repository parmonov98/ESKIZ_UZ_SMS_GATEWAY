<?php
define("SMS_GATEWAY_EMAIL", 'email for your eskiz.uz '); // email eskizuz akkauntdan
define("SMS_GATEWAY_SECRET", 'secret from eskiz.uz cabinet'); // secret key eskizuz accauntdan 
define("SMS_GATEWAY_URL", 'https://notify.eskiz.uz/api/');  // asosiy uri
define('SMS_GATEWAY_TOKEN_FILE', 'sms_gateway_token.json'); // o'ziznikiga almashtirishiz mumkin.
define('SMS_GATEWAY_TOKEN', file_get_contents(SMS_GATEWAY_TOKEN_FILE));  // xohlasayiz fayldan o'qing. 