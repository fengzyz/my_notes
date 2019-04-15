## 目录
- 基础
- CGI
- FastCGI介绍
- FastCGI工作原理
- PHP-FPM工作原理
- 总结

在搭建 LAMP/LNMP 服务器时，会经常遇到 PHP-FPM、FastCGI和CGI 这几个概念。如果对它们一知半解，很难搭建出高性能的服务器。它们是什么概念，这些概念之间有什么关系呢。

### 基础

在网站架构中，web服务器（Nginx、Apache）只是内容的分发者，举个栗子，如果客户端请求的是 index.html，那么Web Server会去文件系统中找到这个文件，发送给浏览器，这里分发的是静态数据。

![image](https://github.com/fengzyz/studynotes/raw/master/images/html.png)

如果请求的是 index.php，根据配置文件，Web Server知道这个不是静态文件，需要去找 PHP 解析器来处理，那么他会把这个请求简单处理，然后交给PHP解析器。

![image](https://github.com/fengzyz/studynotes/raw/master/images/cgi.png)

