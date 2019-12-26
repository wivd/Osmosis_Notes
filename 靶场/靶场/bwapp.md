# 搭建bWAPP靶场

下载地址： https://sourceforge.net/projects/bwapp/ 

## 一.下载bWAPP 文件

解压到网站根目录下。

环境： apache+mysql+php的环境 

![image.png](http://ww1.sinaimg.cn/large/007bHQE8ly1g8o3ojwjupj30mn0acjuf.jpg)

## 二. 配置数据库文件。

修改bWAPP/admin目录下的settings.php文件

- (必须修改)
- $db_server = "localhost";
  $db_username = "root";
  $db_password = "123456";
  $db_name = "bWAPP"; 
- (可选修改)
-  $smtp_sender = "bwapp@mailinator.com";
  $smtp_recipient = "bwapp@mailinator.com";
  $smtp_server = ""; 

### 三.安装部署

访问路径：http://127.0.0.1/bWAPP_latest/bWAPP/install.php

![image-20191106101847242](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\image-20191106101847242.png)

点击here 安装部署站点。

访问：http://127.0.0.1/bWAPP_latest/bWAPP/login.php

![image.png](http://ww1.sinaimg.cn/large/007bHQE8ly1g8o3urrtljj30pw0i4qau.jpg)

登录密码bee/bug ，验证部署成功。