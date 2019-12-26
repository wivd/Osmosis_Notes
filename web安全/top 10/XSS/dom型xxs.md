https://blog.csdn.net/SKI_12/article/details/85648582

基本概念
DOM，全称Document Object Model，是一个平台和语言都中立的接口，可以使程序和脚本能够动态访问和更新文档的内容、结构以及样式。

DOM型XSS其实是一种特殊类型的反射型XSS，它是基于DOM文档对象模型的一种漏洞，其触发不需要经过服务器端，也就是说，服务端的防御并不起作用。

在网站页面中有许多页面的元素，当页面到达浏览器时浏览器会为页面创建一个顶级的Document object文档对象，接着生成各个子文档对象，每个页面元素对应一个文档对象，每个文档对象包含属性、方法和事件。可以通过JS脚本对文档对象进行编辑从而修改页面的元素。也就是说，客户端的脚本程序可以通过DOM来动态修改页面内容，从客户端获取DOM中的数据并在本地执行。基于这个特性，就可以利用JS脚本来实现XSS漏洞的利用。

 

可能触发DOM型XSS的属性
document.referer属性

window.name属性

location属性

innerHTML属性

documen.write属性

······

 

Low级别
点击正常功能观察：



查看页面源码，可以看到以下框中的JS代码，从URL栏中获取default参数的值，这里是通过获取“default=”后面的字符串来实现的，然后直接写到option标签中，并没有对特殊字符进行任何的过滤：



可以明确，这是由document.write属性造成的DOM型XSS漏洞。

因为这段JS代码是本地执行的，获取本地输入的URL栏上的default参数再直接嵌入到option标签中的，因而可以直接往default参数注入XSS payload即可：

<script>alert(document.cookie)</script>


检测元素，可以看到是通过JS在本地动态执行嵌入了script标签：



若要尝试使用其他XSS payload，如img、svg等标签，因为select标签内只允许内嵌option标签，而option标签中能内嵌script标签但不能内嵌img等标签，因此需要在注入时先闭合option和select标签从而使注入的标签逃逸出来执行XSS：

</option></select><img src=x οnerrοr=alert("SKI12")>





最后查看源码，没有做任何防御：


