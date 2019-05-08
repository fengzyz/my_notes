## 高并发系统内核优化
+   [Socket优化](#Socket)
    +   Nginx
    +   系统内核
+   [文件优化](#file)
    +   Nginx
    +   系统内核    
+   [配置文件优化](#config-file)
    +   Nginx配置文件
    +   内核配置文件      
    +   PHP7配置文件      
    +   PHP-FPM配置文件      
    +   php-fpm.conf 重要参数详解
###  <a name="Socket"/> Socket优化
#### Nginx 
+   子进程允许打开的连接数：`worker_connections`
#### 系统内核 
+   [内核参数的优化](http://blog.csdn.net/moxiaomomo/article/details/19442737)  
+   实践优化配置
    +  编辑： `vim /etc/sysctl.conf`
    +  配置结果
    
        ```bash
        net.ipv4.tcp_max_tw_buckets = 6000
        net.ipv4.ip_local_port_range = 1024    65000
        net.ipv4.tcp_tw_recycle = 1
        net.ipv4.tcp_tw_reuse = 1
        net.ipv4.tcp_syncookies = 1
        net.core.somaxconn = 262144
        net.core.netdev_max_backlog = 262144
        net.ipv4.tcp_max_orphans = 262144
        net.ipv4.tcp_max_syn_backlog = 262144
        net.ipv4.tcp_syn_retries = 1
        net.ipv4.tcp_fin_timeout = 1
        net.ipv4.tcp_keepalive_time = 30
        ```
    +   执行命令使之生效：`/sbin/sysctl -p`       
###   <a name="file"/> 文件优化
#### Nginx 
+   指当一个nginx进程打开的最多文件描述符数目：`worker_rlimit_nofile 100000;`
#### 系统内核 
+   系统限制其最大进程数：`ulimit -n`
+   编辑文件：`/etc/security/limits.conf`

    ```conf
    # End of file
    root soft nofile 65535
    root hard nofile 65535
    * soft nofile 65535
    * hard nofile 65535
    ```       
###  <a name="config-file"/> 配置文件优化
+   Nginx配置文件

    ```lua
    user  www www;
    worker_processes 8;
    worker_cpu_affinity 00000001 00000010 00000100 00001000 00010000 00100000 01000000;
    error_log  /www/log/nginx_error.log  crit;
    pid        /usr/local/nginx/nginx.pid;
    worker_rlimit_nofile 204800;
    
    events
    {
      use epoll;
      worker_connections 204800;
    }
    
    http
    {
      include       mime.types;
      default_type  application/octet-stream;
    
      charset  utf-8;
    
      server_names_hash_bucket_size 128;
      client_header_buffer_size 2k;
      large_client_header_buffers 4 4k;
      client_max_body_size 8m;
    
      sendfile on;
      tcp_nopush     on;
    
      keepalive_timeout 60;
    
      fastcgi_cache_path /usr/local/nginx/fastcgi_cache levels=1:2
      keys_zone=TEST:10m
      inactive=5m;
      fastcgi_connect_timeout 300;
      fastcgi_send_timeout 300;
      fastcgi_read_timeout 300;
      fastcgi_buffer_size 64k;
      fastcgi_buffers 8 64k;
      fastcgi_busy_buffers_size 128k;
      fastcgi_temp_file_write_size 128k;
      fastcgi_cache TEST;
      fastcgi_cache_valid 200 302 1h;
      fastcgi_cache_valid 301 1d;
      fastcgi_cache_valid any 1m;
      fastcgi_cache_min_uses 1;
      fastcgi_cache_use_stale error timeout invalid_header http_500;
    
      open_file_cache max=204800 inactive=20s;
      open_file_cache_min_uses 1;
      open_file_cache_valid 30s;
      tcp_nodelay on;
    
      #gzip  on;
      gzip on;
      gzip_min_length 1k;
      gzip_buffes 16 64k;
      gzip_http_version 1.1;
      gzip_comp_level 6;
      gzip_types text/plain application/x-javascript text/css application/javascript text/javascript image/jpeg image/gif image/png application/xml application/json;
      gzip_vary on;
      gzip_disable "MSIE [1-6].(?!.*SV1)";
      
      index  index.php index.html index.htm;
      
      server
      {
          listen       8080;
          server_name  backup.aiju.com;
          root  /www/html/;  #这里的位置很重要，不要写在其它指令里面，我曾经就调试了好久才发现这个问题的
    
          location /status
          {
            stub_status on;
          }
      
          location ~ .*\.(html|htm|gif|jpg|jpeg|bmp|png|ico|txt|js|css)$ {
                  #root /home/www/sansan-web/public;
                  expires      3d;
          }
  
          location ~ ^/(status|ping)$
          {
                  include fastcgi_params;
                  fastcgi_pass unix:/var/run/php7.0.22-fpm.sock;
                  fastcgi_param SCRIPT_FILENAME $fastcgi_script_name;
          }
  
          location = /favicon.ico {
              access_log off;
          }
  
          error_page  400 401 402 403 404  /40x.