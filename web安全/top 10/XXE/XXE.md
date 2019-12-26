## 0x00、XXE漏洞

XXE漏洞全称XML External Entity Injection 即xml外部实体注入漏洞，XXE漏洞发生在应用程序解析XML输入时，**没有禁止外部实体的加载**，导致可加载恶意外部文件和代码，造成**任意文件读取**、**命令执行**、**内网端口扫描**、**攻击内网网站**、**发起Dos攻击**等危害。

XXE漏洞触发的点往往是可以上传xml文件的位置，没有对上传的xml文件进行过滤，导致可上传恶意xml文件。

## XXE是什么？

普通的 XML 注入，这个的利用面比较狭窄，如果有的话应该也是逻辑漏洞

 ![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g85truf34dj30k40b6tbi.jpg)

既然能插入 XML 代码，那我们肯定不能善罢甘休，我们需要更多，于是出现了 XXE

XXE(XML External Entity Injection) 全称为 XML 外部实体注入，从名字就能看出来，这是一个注入漏洞，

### 注入的是什么？

XML外部实体。(看到这里肯定有人要说：你这不是在废话)，固然，其实我这里废话只是想强调我们的利用点是 **外部实体** ，也是提醒读者将注意力集中于外部实体中，而不要被 XML 中其他的一些名字相似的东西扰乱了思维(**盯好外部实体就行了**)，如果能注入 外部实体并且成功解析的话，这就会大大拓宽我们 XML 注入的攻击面（这可能就是为什么单独说 而没有说 XML 注入的原因吧，或许普通的 XML 注入真的太鸡肋了，现实中几乎用不到）

## 0x01、XML基础知识

要了xxe漏洞，那么一定得先弄明白基础知识，了解xml文档的基础组成

XML 指可扩展标记语言（Extensible Markup Language）

```
XML 被设计用来传输和存储数据。
HTML 被设计用来显示数据
```

XML把数据从HTML分离，XML是独立于软件和硬件的信息传输工具。

XML语言没有预定义的标签，允许作者定义自己的标签和自己的文档结构

**XML的语法规则：**

- XML 文档必须有一个根元素
- XML 元素都必须有一个关闭标签
- XML 标签对大小敏感
- XML 元素必须被正确的嵌套
- XML 属性值必须加引导



```
<?xml version="1.0" encoding="UTF-8"?> <!--XML 声明-->
<girl age="18">　　<!--自定的根元素girl;age属性需要加引导-->
<hair>长头发</hair>　　<!--自定义的4个子元素，即girl对象的属性-->
<eye>大眼睛</eye>
<face>可爱的脸庞</face>
<summary>可爱美丽的女孩</summary>
</girl>　　<!--根元素的闭合-->
```



**实体引用**

在 XML 中，一些字符拥有特殊的意义。

如果您把字符 "<" 放在 XML 元素中，会发生错误，这是因为解析器会把它当作新元素的开始。

这样会产生 XML 错误：

```
<message>if salary < 1000 then</message>
```

为了避免这个错误，请用**实体引用**来代替 "<" 字符：

```
<message>if salary &lt; 1000 then</message>
```

在 XML 中，有 5 个预定义的实体引用：

| &lt;   | <    | 小于号 |
| ------ | ---- | ------ |
| &gt;   | >    | 大于号 |
| &amp;  | &    | 和号   |
| &apos; | '    | 单引号 |
| &quot; | "    | 引号   |

 

## 0x02、DTD (`Document Type Definition`)

DTD（文档类型定义）的作用是定义XML文档的合法构建模块

DTD 可被成行地声明于 XML 文档中，也可作为一个外部引用。



```
<!--XML声明-->
<?xml version="1.0"?> 
<!--文档类型定义-->
<!DOCTYPE note [  　　<!--定义此文档是 note 类型的文档-->
<!ELEMENT note (to,from,heading,body)>  <!--定义note元素有四个元素-->
<!ELEMENT to (#PCDATA)>     <!--定义to元素为”#PCDATA”类型-->
<!ELEMENT from (#PCDATA)>   <!--定义from元素为”#PCDATA”类型-->
<!ELEMENT head (#PCDATA)>   <!--定义head元素为”#PCDATA”类型-->
<!ELEMENT body (#PCDATA)>   <!--定义body元素为”#PCDATA”类型-->
]]]>
<!--文档元素-->
<note>
<to>Dave</to>
<from>Tom</from>
<head>Reminder</head>
<body>You are a good man</body>
</note>
```



上述XML代码基本分为三个部分：

第一部分是XML的声明；

第二部分是XML的DTD文档类型定义

第三部分是XML语句

而外部实体攻击主要利用DTD的外部实体来进行注入的。

DTD有两种构建方式，分别为**内部DTD声明**和**外部DTD声明**

**内部DTD声明：**

```
<!DOCTYPE 根元素 [元素声明]>
```

实例：如上述代码

**外部DTD声明：**

```
<!DOCTYPE 根元素 SYSTEM "文件名">
```

实例：



```
<?xml version="1.0"?>
<!DOCTYPE root-element SYSTEM "test.dtd">
<note>
<to>Y</to>
<from>K</from>
<head>J</head>
<body>ESHLkangi</body>
</note>
```



test.dtd

```
<!ELEMENT to (#PCDATA)><!--定义to元素为”#PCDATA”类型-->
<!ELEMENT from (#PCDATA)><!--定义from元素为”#PCDATA”类型-->
<!ELEMENT head (#PCDATA)><!--定义head元素为”#PCDATA”类型-->
<!ELEMENT body (#PCDATA)><!--定义body元素为”#PCDATA”类型-->
```

PCDATA的意思是被解析的字符数据。PCDATA是会被解析器解析的文本。这些文本将被解析器检查实体以及标记。文本中的标签会被当作标记来处理，而实体会被展开。

CDATA意思是字符数据，CDATA 是不会被解析器解析的文本，在这些文本中的标签不会被当作标记来对待，其中的实体也不会被展开。

 

DTD实体同样有两种构建方式，分别为内部实体声明和外部实体声明。

**内部实体声明：**

```
<!ENTITY entity-name "entity-value">
```

实例：



```
<?xml version="1.0">
<!DOCTYPE note [
<!ELEMENT note(name)>
<!ENTITY hacker "ESHLkangi">
]>

<note>
<name>&hacker;</name>
</note>
```

**外部实体声明：**

```
<!ENTITY entity-name SYSTEM "URL/URL">
```

默认协议

![img](https://images2018.cnblogs.com/blog/1312179/201806/1312179-20180629164740124-1248919886.png)

PHP扩展协议

![img](https://images2018.cnblogs.com/blog/1312179/201806/1312179-20180629164803319-1939178476.png)

 

 实例：



```
<?xml cersion="1.0">
<!DOCTYPE hack [
<!ENTITY xxe SYSTEM "file:///etc/password">
]>

<hack>&xxe;</hack>
```



上述代码中，XML的外部实体“xxe”被赋予的值为：file:///etc/passwd

当解析xml文档是，xxe会被替换为file:///ect/passwd的内容。

参数实体+外部实体：

```

<?xml version="1.0" encoiding="utf-8">
<!DOCTYPE hack [
    <!ENTITY % name SYSTEM "file:///etc/passwd">
   %name; 
]>

```

"%name"（参数实体）实在DTD中被引用，而"&name;"是在xml文档中被引用的。

XXE漏洞攻击主要是利用了DTD引用外部实体导致的漏洞。

 

## 0x03、攻击思路

1、引用外部实体远程文件读取

2、Blind XXE

3、Dos

 