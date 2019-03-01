# 环境配置：
```json
{
    "nginx": "*",
    "mysql": "*",
    "php": ">=7.0.0",
    "topthink/framework": "5.0.*",
    "ext-gd": "*",
    "ext-redis": "*",
    "ext-seaslog": "*"
}
```

# `nginx` 服务示例：
```nginxconfig
server {
    listen 80;
    server_name [域名];
    access_log  /home/wwwlogs/tp5-im_access.log;
    error_log   /home/wwwlogs/tp5-im_error.log;
    set         $root   /home/www/tp5-im/public;
    location ~ .*\.(gif|jpg|jpeg|bmp|png|ico|txt|js|css)$
    {
        root $root;
    }
    location / {
        root    $root;
        index   index.html index.php;
        if ( -f $request_filename) {
            break;
        }
        if ( !-e $request_filename) {
            rewrite ^(.*)$ /index.php/$1 last;
            break;
        }
    }
    location ~ .+\.php($|/) {
        fastcgi_pass    unix:/tmp/php-cgi.sock;
        fastcgi_split_path_info ^((?U).+.php)(/?.+)$;
        fastcgi_param   PATH_INFO          $fastcgi_path_info;
        fastcgi_param   PATH_TRANSLATED    $document_root$fastcgi_path_info;
        fastcgi_param   SCRIPT_FILENAME    $root$fastcgi_script_name;
        include         fastcgi_params;
    }
}
```

# MySQL
> `sql/im.sql`

# 配置文件
- `application/local_config.php` 

```php
<?php
$SeasLog_base_path = __DIR__ . '/../runtime/seaslog';
SeasLog::setBasePath($SeasLog_base_path);

return [
    // 应用调试模式
    'app_debug'                 => true,
    // 网易云信AppKey
    'IM_AppKey'                 => '***',
    // 网易云信AppSecret
    'IM_AppSecret'              => '***',
    // SeasLog日志配置
    'SeasLog_base_path'         => $SeasLog_base_path,
];
```

- `application/local_database.php`

```php
<?php
return [
    // 服务器地址
    'hostname'        => 'localhost',
    // 数据库名
    'database'        => 'im',
    // 用户名
    'username'        => 'root',
    // 密码
    'password'        => '***',
];
```

# 接口文档
[看云 - tp5-im API](https://www.kancloud.cn/eson_sheng/tp5-im)