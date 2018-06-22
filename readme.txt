如果是Apache服务器，在 project 目录下新建一个 .htaccess 文件，内容为：
<IfModule mod_rewrite.c>
    # 打开Rerite功能
    RewriteEngine On
    # 如果请求的是真实存在的文件或目录，直接访问
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # 如果访问的文件或目录不是真事存在，分发请求至 index.php
    RewriteRule . index.php
</IfModule>
如果是Nginx服务器，修改配置文件，在server块中加入如下的重定向：
location / {
    try_files $uri $uri/ /你的项目路径/index.php$args;
}
//参考张辉nginx配置
 location / {
	fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root/index.php;
        include        fastcgi_params;
 }