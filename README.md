# CI_Scaffolding


## 概要
主要是基于CodeIgniter框架的开发包,前端部分基于webpack+vue.js,便于快速搭建开发环境,适合中小应用的开发。
里面引入自动化构建,包括:
1. 单元测试
2. 代码静态检测
3. 自动部署
4. 前后端分离开发

Scaffolding在开发中共有三个分支,分别为:

   * dev:开发版本
   * test:测试版本
   * master:线上版本

在过程中我们会采用git flow 工作流。

可以参考:
[Git工作流指南：Gitflow工作流](http://blog.jobbole.com/76867/)

## 项目结构
该项目是基于[CodeIgniter](http://codeigniter.org.cn/)
CI_Scaffolding
├──application <- 框架自带目录 内部结构不在复述  
├──static <- 存放静态文件 由于基于webpack+vue 后面会详细介绍下  
├──system <- 框架自带目录  
├──.htaccess  
├──build.properties <- 自动化构建的配置 jenkins平台 尚未集成  
├──build_win.properties <- 自动化构建的配置 windows平台 一般指windows 开发环境 尚未集成  
├──build.xml <- 基于phing的自动化构建脚本 尚未集成  
├──CI_phpstrom.php <- phpstorm下面的CI框架代码提示  
├──index.php <- 框架自带  
└──php.properties <- 自动化脚本使用的php环境配置 尚未集成  

## 前端部分
#### 目录结构
static
├──assets <- 此目录由webpack 自动生成
├──css <- 存放css
├──img <- 存放图片
├──js
│  ├──class <- 存放公共的类
│  ├──components <- 存放vue 组件
│  ├──lib <- 所依赖的第三方库
│  ├──helper <-工具js文件
│  ├──fliters <- vue 过滤器
│  └──其它 <- 各个模块对于的js文件
├──package.json <- node配置
└──webpack.config.js <- webpack 配置

#### 前端build 代码尚未集成
前提你已经安装了[node.js](https://nodejs.org/en/)

首先在`static`目录下执行:

```
npm install
```
下载所安装的依赖包。

也可以配置国内npm镜像然后执行

```
cnpm install
```
[国内npm镜像配置方法](https://npm.taobao.org/)

开发环境执行

```
 npm run dev
```

```
mvn package
```
就会自动生成`assets`目录,里面包含项目所引用的js文件

测试环境前端build执行

```
npm run test
```

生产环境前端build执行

```
npm run pro
```

## 服务端
尚未完成


## 自动化构建
1. 自动化构建主要完成以下几件事:
    * 编译前端代码
    * 生成最新前端url后缀,防止版本缓存
    * 自动生成对应版本所需配置文件
    * 部署到测试服务器

2. 后期会引入:
    * 单元测试
    * 代码check
    * 一些列代码静态检查

尚未完成


未完待续!!!
