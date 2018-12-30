# Laravel-sosad 后端运行指南
##注意事项
前后端分离之后，所有laravel相关的指令，比如`php artisan migrate`，或者composer相关指令，比如`composer update`，都应进入backend文件夹之后操作，否则会报错。
## 安装
第一次使用时，运行
```
$composer update
```
补完需要加载的框架文件

下一步，将`.env.example`改写为`.env`，补完其中关于database信息的内容，其中环境变量DB_DATABASE应指向空白的本地mysql数据库。mac系统推荐使用sequel pro浏览数据库的情形。

接下来，运行数据库migration，并通过预先写好的seeder，给数据库填充用于测试的假信息
```
$php artisan migrate
$php artisan db:seed
```

为了让oauth能够运行，还需要加载初始key
```
$php artisan passport:keys
```
还需要给Personal access client进行授权
```
$php artisan passport:client  --personal
```
这一步输入任意字符串即可。

使用valet的用户，只要进行到`backend`根目录再往外的地方，运行
```
$valet park
```
以后就可以通过`backend.test`这个网址对本工程进行访问。比如，访问`backend.test/api/register`，进行新用户的注册。

未使用valet的用户，可以运行指令
```
$php artisan serve
```
使用弹出的地址，访问本工程。一般是`http://127.0.0.1:8000/`。也就是说，以后可以使用`http://127.0.0.1:8000/api/register`，访问注册页面

以下默认使用`http://127.0.0.1:8000/`作为访问路径

## 注册新用户
打开postman，选择POST方式发送信息（记得不要变成GET！）
网址设置为`http://127.0.0.1:8000/api/register`
在下拉parameter内容表单中填写内容，冒号左边是变量名称，冒号右边是对应内容。可以在postman界面中保存相关指令，便于后续重试：
name: tester
email: tester@example.com
password: password
password_confirmation: password
这里字串'password'是默认的密码，也可以设置成其他字串。
然后点击发送，就会收到成功格式的json返回信息，其中`code:200`表示成功，所返回的`token`就是之后用户用于登陆的验证信息。
如果信息不符合要求，会出现对应的validation错误提示。

## 普通登陆
普通登陆也可以获得token用于进一步访问，方法是：
网址设置为`http://127.0.0.1:8000/api/login`，选择POST方式发送信息
在下拉内容中填写：
email: tester@example.com
password: password
然后点击发送，就可以和上面一步一样，得到正确的访问token
如果这里发生了信息输入错误，导致信息于用户列表不匹配，这里会收到对应的错误代码：
401:'unauthorised'

## 得到token之后，使用token获取所需要的信息
前端默认使用
```
'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer '.$accessToken,
]
```
这样的格式，来表示自己是api终端，需要以xx用户的身份通过验证.在postman中下拽header并填写就行了，其他parameter照常填写在parameter部分。

## 错误处理 error
全部error 列表目前存放在 config/error.php中

## 首页信息呈现
http://127.0.0.1:8000/api
（不需要使用token登陆信息）

## 获得全部tag信息
http://127.0.0.1:8000/api/config/allTags
（不需要使用token登陆信息）

## 获得讨论帖/书籍index信息
http://127.0.0.1:8000/api/thread
视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容）
可选的筛选变量及效果：
InChannel=1_2_3(返回在channel1，2，3中的讨论帖)
withBook=book_only//none_book_only(是否仅返回书籍/讨论帖信息)
withTag=1_22_4(仅返回含有1，22，4这几个tag的书籍/讨论帖)
excludeTag=1_22_4(仅返回不含有1，22，4这几个tag的书籍/讨论帖)
withBianyuan=bianyuan_only//none_bianyuan_only（是否仅返回边缘/非边缘书籍/讨论帖）
ordered=last_added_chapter_at（按最新更新时间排序）//jifen（按总积分排序）//weighted_jifen（按平衡积分排序）//created_at（按创建时间排序）//id（按id排序）//collections（按收藏总数排序）//total_char（按总字数排序）

## 获得讨论帖首页信息（首楼，及首页的回帖）
http://127.0.0.1:8000/api/thread/1
按讨论帖格式，返回id=1（id可以更换成其他数字）的讨论帖首页信息：thread,channel,tags,posts,pagination
视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容，只有符合要求的用户才能获得相关信息）

## 获得书籍首页信息（首楼，章节列表）
http://127.0.0.1:8000/api/book/1
按书籍格式，返回id=1（id可以更换成其他数字）的书籍首页信息。thread,channel,tags,chapters（章节）,volumns（分卷）,pagination
视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容，只有符合要求的用户才能获得相关信息）
