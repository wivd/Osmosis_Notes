# Awvs 12安装教程

一，准备环境：

win10

Awvs12 安装包

二，安装Awvs12

1.直接解压出安装包压缩文件，点击开始安装进程。

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qk6ryn4oj30iq07st9k.jpg)

2.选择next进行安装。



![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qka41idnj30dz0atdgb.jpg)

3.同意其条款。

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkcgyal1j30dz0at3z3.jpg)

4.填写邮箱，密码和确认密码，

注意密码的复杂程度。

![1570471613129](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1570471613129.png)

5.选择Allew remote access to Acunetix

然后选择 next

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkh9xfeaj30dz0atjrs.jpg)

6.继续选择next

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkif0muaj30dz0at3ys.jpg)

7.点击 install

内容为个人信息和安装路径

![1570471818559](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1570471818559.png)

8.等待安装完成

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkkb923kj30dz0atjrl.jpg)

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkkm2pe8j30dz0atmxj.jpg)



二，登录

开始登录

![1570471988632](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1570471988632.png)



三，激活

1.复制补丁包至安装的目录内

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qkrs1tgrj31fj0lidjb.jpg)

2.以管理员的身份打开并应用该补丁包

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7qktjk4ymj30hl0doq3t.jpg)

 ![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203203855708-1208736118.png)

如果应用后报错，请删除补丁包，重新复制，再以管理员的身份去应用，即可成功。

![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203203924017-1655157404.png)

应用成功后，点确定关闭提示框；

在新的对话框内录入信息

![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203203945212-1137003854.png)

如果报错，请回去检查数据格式是否正确；

![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203204011165-923007684.png)

![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203204023868-862314260.png)

![img](https://img2018.cnblogs.com/blog/1452244/201812/1452244-20181203204037646-622462107.png)



四，使用时报无权限的解决方法

破解版的有个问题，就是破解后，只能执行一次，再次使用需要再次破解；这里本人建议使用每次破解的方法。那种一次性授权的方式，部分电脑上没法用。

实操步骤：

1）将破解补丁包创建快捷方式到桌面，这样就不用每次都得文件夹里找；

2）每报一次无权限时，回到桌面，点击补丁包的快捷方式，走完注册流程即可使用一次；

注意事项：不用关闭网页，直接打开补丁包执行激活流程；激活成功后，刷新页面即可

五，其他awvs12 破解版使用方法。

 先安装Awvs12
后将补丁放入安装目录管理员权限执行 
taskkill /im Activation.exe /f&&taskkill /im wvsc.exe /f
管理员权限执行补丁
注册信息随便填！

激活之后 管理员权限执行以下命令 防止反复注册
cacls “C:\ProgramData\Acunetix\shared\license.” /t /p everyone:r  

六，汉化

**汉化包是没有的，awvs12版网上没有汉化版，不过可以使用其他方法；这里推荐使用360极速浏览器，然后启用极速模式，页面会弹出翻译，这个浏览器翻译很好用，建议优先使用谷歌来翻译，这个翻译的准确性更高。**