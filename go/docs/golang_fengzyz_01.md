### 什么是Golang
Golang是一种开源的，由Google创建的编译和静态类型编程语言,它意在使人们能够更方便的构建简单、可靠、高效的软件。  

Golang的主要重点是使高度可用和可扩展的网络应用程序的开发变得简单和容易。
### 安装
Golang在所有三种平台的Mac，Windows和Linux（FreeBSD、UNIX）上均受支持。

- 下载二进制包：go1.4.linux-amd64.tar.gz。
   
- 将下载的二进制包解压至 /usr/local目录

> tar -C /usr/local -xzf go1.4.linux-amd64.tar.gz

- 将 /usr/local/go/bin 目录添加至PATH环境变量：
> export PATH=$PATH:/usr/local/go/bin

> 注意：MAC 系统下你可以使用 .pkg 结尾的安装包直接双击来完成安装，安装目录在 /usr/local/go/ 下。

Windows 系统下安装

Windows 下可以使用 .msi 后缀(在下载列表中可以找到该文件，如go1.4.2.windows-amd64.msi)的安装包来安装。

默认情况下.msi文件会安装在 c:\Go 目录下。你可以将 c:\Go\bin 目录添加到 PATH 环境变量中。添加后你需要重启命令窗口才能生效。

