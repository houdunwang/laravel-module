## 介绍

通过使用模块来管理大型Laravel项目。模块就像一个laravel包。这个包已经在 [HDCMS](http://www.hdcms.com) 中使用。

模块是在 [nwidart.com/laravel-modules](https://nwidart.com/laravel-modules/v3/advanced-tools/artisan-commands) 组件基础上扩展了一些功能。

> nwidart.com/laravel-modules 组件的功能都可以正常使用

## 安装

    composer require houdunwang/laravel-modules
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"

配置 composer.json 设置自动加载目录

    {
      "autoload": {
        "psr-4": {
          "App\\": "app/",
          "Modules\\": "Modules/"
        }
      }
    }

## 命令

#### 创建模块

```
php artisan hd:module Admin
```
#### 生成配置文件

下面命令生成模块的初始配置文件，创建模块时系统会自动执行。

```
php artisan hd:config Admin
```

## 模块权限

权限操作依赖 [laravel-permission](https://github.com/spatie/laravel-permission#installation) 进行权限管理，所以首先需要安装。

> 不要重复安装！会重复生成数据迁移文件，导致执行数据迁移出错。

#### 安装 laravel-permission

```
composer require spatie/laravel-permission

#生成迁移文件，根据业务需要可以随意添加表字段
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

php artisan migrate
```

> 认真检查每一步要安装正确

#### 设置权限

根据 `Admin` 模拟的 `Config/permission.php` 权限配置文件生成权限表数据

```
php artisan hd:permission Admin
```

> 必须存在正确配置的 `Config/permission.php` 文件

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
