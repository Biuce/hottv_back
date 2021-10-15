# SnacaCMS
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eddy8/SnacaCMS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eddy8/SnacaCMS/?branch=master)    [![StyleCI](https://github.styleci.io/repos/175428969/shield?branch=master)](https://github.styleci.io/repos/175428969)    [![Build Status](https://www.travis-ci.org/eddy8/SnacaCMS.svg?branch=master)](https://www.travis-ci.org/eddy8/SnacaCMS)    [![PHP Version](https://img.shields.io/badge/php-%3E%3D7.2-8892BF.svg)](http://www.php.net/)

## 项目简介
`SnacaCMS`是一个轻量级的`CMS`系统，也可以作为一个通用的后台管理框架使用。`SnacaCMS`集成了用户管理、权限管理、日志管理、菜单管理等后台管理框架的通用功能等`CMS`系统中常用的功能。

`SnacaCMS`基于`Laravel 6.x`开发

## 系统环境
`linux/windows & nginx/apache/iis & mysql 5.5+ & php 7.2+`

* PHP >= 7.2.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

**注意事项**

* 如果缓存、队列、session用的是 redis 驱动，那还需要安装 redis 和 php redis 扩展
* 如果`PHP`安装了`opcache`扩展，请启用`opcache.save_comments`和`opcache.load_comments`配置（默认是启用的），否则无法正常使用[菜单自动获取](#菜单自动获取)功能

## 系统部署

### 获取代码并安装依赖
首先请确保系统已安装好[composer](https://getcomposer.org/)。国内用户建议先[设置 composer 镜像](https://developer.aliyun.com/composer)，避免安装过程缓慢。
```bash
cd /data/www
git clone git_repository_url
cd SnacaCMS
composer install
```
### 系统配置并初始化
设置目录权限：`storage/`和`bootstrap/cache/`目录需要写入权限。
```bash
# 此处权限设置为777只是为了演示操作方便，实际只需要给web服务器写入权限即可
sudo chmod 777 -R storage/ bootstrap/cache/
```
新建一份环境配置，并配置好数据库等相关配置:
```base
cp .env.example .env
```
初始化系统：
```base
php artisan migrate --seed
```

### 配置Web服务器（此处以`Nginx`为例）
```
server {
    listen 80;
    server_name snaca.com;
    root /data/www/SnacaCMS/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #不同配置对应不同的环境配置文件。比如此处应用会加载.env.pro文件，默认不配置会加载.env文件。此处可根据项目需要自行配制。
        #fastcgi_param   APP_ENV pro;
        include fastcgi_params;
    }
}
```

## 完善中。。。

