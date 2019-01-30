# Laravel-sosad 后端运行指南
## 1.序言
本文档是为用户理解和使用backend文件夹中的所有内容所准备的。backend的主要任务，是提供一套方便使用的api。本文档内容包含安装配置，数据库表格设计的解释，以及api各个指令的属性、所需数据的格式、返回结果的格式。
#### 特别注意事项
前后端分离之后，所有laravel相关的指令，比如`php artisan migrate`，或者composer相关指令，比如`composer update`，都应进入backend文件夹之后操作，否则会报错。本文档中所有“根目录”，如未特别说明，指的都是居于backend文件夹内部的这个地址。
## 2.安装配置
#### 2.1 composer更新组件
第一次使用时，运行
```
$ composer update
```
补完需要加载的框架和package文件。
#### 2.2 书写和加载`.env`文件中数据库配置

laravel后端中所有环境变量（基本配置比如数据库地址、用户名、密码，和大部分不应被git上传的安全相关的文件比如加密所需的key）都存放在居于最外面的`.env`文件。laravel已经准备了范例，只需在范例基础上稍作加工，就可以得到自己的这个文件。将backend根目录下`.env.example`改写为`.env`，补完其中关于database信息的内容。  
其中环境变量`DB_DATABASE`应指向空白的本地mysql数据库，需要用户自己安装mysql，并创建一个新的数据库，将它的地址和用户名、密码填到这个地方。mac系统推荐使用sequel pro程序浏览自己本地mysql数据库的情形。
#### 2.3 数据库migration，使用seeder填充mock数据
接下来，运行数据库migration，并通过预先写好的seeder，给数据库填充用于测试的mock信息   
```
$ php artisan migrate --seed
```
#### 2.4 配置passport
###### 2.4.1 配置APP_KEY
如果是第一次使用laravel， `.env` 文件中不含APP_KEY这个变量，那么还需要让程序加载初始key。一些情况下，也可以使用以前曾经使用过的key，来确保数据库之间能够对应。只需运行下面这两个指令即可：
```
$ php artisan key:generate
$ php artisan passport:keys
```  
如果之前已经配置了key，程序会提示你，是否想要重置key，按自己需求选择即可。

###### 2.4.2 创建passport client
在这个工程中，我们使用laravel自带的passport这个package，给api进行基本的授权。为了在本地顺利测试相关情况，我们需要对passport进行基本配置，比如说，以Personal access client的名义，给自己的前端部分授权。
```
$ php artisan passport:client  --personal
```

这一步按照程序提示，输入任意字符串即可。
#### 2.5 使用valet，或直接serve程序，让服务器“运行”起来。
###### 2.5.1 使用valet（mac用户，推荐使用此项）
valet是一个非常便利轻量开发的工具，它可以让编程和测试更方便地衔接起来。使用mac的用户，花费一点点时间配置它，事半功倍。  
使用valet的用户，只要进行到`backend`文件夹根目录，运行
```
$ valet park
```
以后就可以通过`backend.test`这个网址对本工程进行访问。比如，访问`backend.test/api/register`，进行新用户的注册。

###### 2.5.2 使用laravel自带的serve指令，模拟服务器服务
不愿使用valet的用户，可以运行指令  
```
$ php artisan serve
```
然后使用terminal中弹出的地址访问本工程即可。一般是`http://127.0.0.1:8000/`。也就是说，以后可以使用`http://127.0.0.1:8000/api/register`，访问注册页面

以下默认使用`http://127.0.0.1:8000/`作为访问路径。

## 3. 数据库结构解释


#### 3.1 数据库简单介绍

###### 3.1.1 废文网数据库的基本情况
关于数据库的ER图，请参考`backend`下文件`ER_of_sosad.png`。这个图还会根据具体情况作出调整。
###### 3.1.2 数据库最重要的三个表格：users，threads，posts
**站内最主要的表格是三个：_users，threads，posts。_**  
**users表**
存放一个用户的最基本数据，比如：用户名、注册日期、是否允许陌生人私信、最后使用的马甲……  
**threads表**
存放一本书或者一个讨论帖（在本站，一本“书籍”=一个“讨论帖”）的全部数据，比如：标题，简介，文案，是否采用markdown语法，是否采用段首缩进，是否允许跟帖，是否公开发布……  
**posts表**
存放一个讨论帖里每一个单独的回帖的信息，比如作者是谁，属于哪个thread，回帖的文本是什么，使用的格式……
<br>
这**三个表之间的逻辑关系**非常简单：  
* 一个user可以发布多个thread。  
* 一个thread可以有多个post。  
* 一个thread属于且只属于一个user。  
* 一个post属于且只属于一个thread。  
###### 3.1.3 对书籍/讨论帖系统的授权管理：channels，tags
**channel（频道/板块）**  
channel是对thread进行统一管理的一种方式。  
站内一共预设有**12个channel**，分别是“原创小说”，“同人小说”，“作业专区”，“日常闲聊”……  
每个channel除了本身的固有属性（名称，定义，版规等字串）之外有自己的**管理逻辑**。比如，  
* type 属性管理着一个channel里的讨论帖的实际状态，可以取的值有：'book'/'thread'/'collection_list'/'column'/'request'/'homework'  
* allow_anonymous 属性决定普通用户是否能够在本channel中匿名发布内容
* allow_edit 属性决定普通用户是否能在本channel中修改thread和post
* is_public 属性决定这个channel里的thread是否对外公开可见（如果不属于public，意味着只有注册参加作业的同学、编辑和管理，才能够进入该channel查看特殊内容）
* on_homepage 属性决定这个channel里的thread是否在首页显示（否则的话，这整个channel不在论坛板块首页显示，而是安排另外的入口）  
<br>

**tag(标签)**  
标签是对thread进行标记的另一种方式  
tag和thread之间具有多对多的关系，这个关系存储在名叫tag_thread的表格里。
一个thread可以有多个tag，一个tag也可以对应多个thread。
tag具有不同的类型（tag_type）,有的时候，也存在一些现象，就是某些tag不能同时标记一个thread，因为它们不符合语义。比如说，一个thread（书籍）不能既是“HE”（tag_name="HE"），又是“BE”（tag_name="BE"）。又比如，一个thread，不能既是“长篇”，又是“短篇”。对于这些限制，描述在tag_type（tag具有不同的类型，比如说“篇幅”，“进度”，“结局”……，某些类型的tag只能最多选一个，也有些类型的tag可以同时选多个）中，在`config/tag.php`里记录了它对应的关系。

###### 3.1.4 用户身份授权管理：roles, role_user
特殊身份的用户，具有特别的权限/限制。比如，一个已经“隐藏”（thread->is_public = false）的thread，不是作者本人就不能查看它的内容。但是管理员具有查看它的权限。又比如，编辑能够审核批准题头。再比如，有一些用户因为违禁，一段时间不能登陆（被关小黑屋）/不能发帖（被禁言）/不能注册参加作业……  
因此，将用户授予特别的身份（role），来对不同的role进行权限管理。  
一个user可以有多个role，一个role可以赋予多个user，因此这也是多对多的关系，存储在role_user表格里。  
因为用户身份这个信息不容易发生变化，将它存储在`config/role.php`中。
在`app/Models/User.php` 中，写了几个方法来调用user的role，和user具体被允许的操作。比如，`user->canSeeChannel(10)`,可以用来查询这个用户能不能“看”第10个channel的内容.

## 4. API文档
建议下载并使用postman程序，对api进行测试。
注意：Postman更改method，比如之前是GET后来是POST，有时需要【保存】才能生效。建议经常duplicate指令并将其命名保存下来，便于以后测试。
#### 4.1 authentification 权限管理
本后端采取passport对用户授权与否进行管理。其授权的基础，是采取接受token，并核对token是否属于和数据库匹配的有效token，从而验证是否能够允许用户对应的操作。
###### 4.1.1 注册新用户（register）
打开postman，选择POST方式发送信息（记得不要变成GET！）  
网址设置为`http://127.0.0.1:8000/api/register`  
在下拉parameter表单中填写内容，冒号左边是变量名称，冒号右边是对应内容。可以在postman界面中保存相关指令，便于后续重试：  
name: tester  
email: tester@example.com  
password: password  
password_confirmation: password  
这里字串`password`是默认的密码，也可以设置成其他字串。
然后点击发送，成功的话就会收到格式为json的返回信息，其中`code:200`表示成功，所返回的`token`就是之后用户用于登陆的验证信息。
如果信息不符合要求，会出现对应的validation错误提示。

###### 4.1.2 普通登陆（login）
已经注册的用户，也可以通过输入用户邮箱和密码登陆，来获得token用于进一步访问，方法是：
网址设置为`http://127.0.0.1:8000/api/login`，选择POST方式发送信息  
在下拉内容中填写：  
email: tester@example.com  
password: password  
然后点击发送，就可以和上面一步一样，得到正确的访问token  
如果这里发生了信息输入错误，导致信息不匹配（比如说，邮箱输错，或者密码输错），这里会收到对应的错误代码：  
401:'unauthorised'  
全部错误列表，可以从`config/error.php`查看  

###### 4.1.3 使用token，以注册用户的身份进行操作
前端默认使用
```
'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer '.$accessToken,
]
```
这样的格式，来表示自己是api终端，需要以xx用户的身份通过验证。
其中$accessToken应该是在之前的login步骤中获得的。  
在postman中下拽header并填写这部分内容就行了。  
实际在postman的Headers（菜单上第三格，不是默认的，需要鼠标点开来）上面显示的效果如下：
| Key       | Value       | 备注  |
| -------------|-------------|-------------|
| Accept  | application/json |照着写就行|
| Authorization | Bearer eyJ0eXAiOiJK...|这个token字串会很长，注意Bearer和token之间有一个英文空格|



#### 4.2 错误处理 error handling
全部error 列表目前存放在 config/error.php中

#### 4.3 固定页面信息呈现（比如主页信息表现）

###### 4.3.1 首页信息呈现
http://127.0.0.1:8000/api  
方法：GET  
授权：不需要使用token登陆  

###### 4.3.2 获得全部tag信息
http://127.0.0.1:8000/api/config/allTags  
方法：GET  
授权：不需要使用token登陆  

###### 4.3.3 文库首页(有待进一步补充信息)  
http://127.0.0.1:8000/api/homebook  
方法：GET  
授权：不需要使用token登陆  

###### 4.3.4 论坛首页(有待进一步补充信息)  
http://127.0.0.1:8000/api/homethread  
方法：GET  
授权：不需要使用token登陆

#### 4.4 资源页面信息呈现（比如目录检索，某一本书的首页信息）

###### 4.4.1 获得讨论帖index信息——这部分需要重新制作
http://127.0.0.1:8000/api/thread  
方法：GET  
授权：可选登陆与否，视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容）  
可选的筛选变量及效果：

channel(array)=[1,2,3] （只返回出现在channel1，2，3中的讨论帖)  
withType(string)='thread'/'book'/'collection_list'/'column'/'request'/'homework' （是否仅返回书籍/讨论帖/收藏单/信息)  
tag(array)=[1,22,4]（仅返回含有1，22，4这几个tag的书籍/讨论帖)  
excludeTag(array)=[1,22,4]（仅返回不含有1，22，4这几个tag的书籍/讨论帖)  
withBianyuan(string)='bianyuan_only'/'none_bianyuan_only'（是否仅返回边缘/非边缘内容）  
ordered(string)='last_added_component_at'/'jifen'/'weighted_jifen'/'created_at'/'id'/'collections'/'total_char'（按最新更新时间排序/按总积分排序/按平衡积分排序/按创建时间排序/按id排序/按收藏总数排序/按总字数排序）  

###### 4.4.1.1 获得书籍index信息——专供文库检索  
http://127.0.0.1:8000/api/book  
方法：GET  
授权：可选登陆与否，视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容）  
可选的筛选变量及效果：  
channel(array)=[1,2,3] （只返回出现在特定channel中的书籍，比如只显示同人小说书籍/只显示原创小说书籍，不选则默认显示全部书籍)    
tag(array)=[1,22,4]（仅返回含有1，22，4这几个tag的书籍)  
excludeTag(array)=[1,22,4]（仅返回不含有1，22，4这几个tag的书籍/讨论帖)  
withBianyuan(string)='bianyuan_only'/'none_bianyuan_only'（是否仅返回边缘/非边缘内容）  
ordered(string)='last_added_component_at'/'jifen'/'weighted_jifen'/'created_at'/'id'/'collections'/'total_char'（按最新更新时间排序/按总积分排序/按平衡积分排序/按创建时间排序/按id排序/按收藏总数排序/按总字数排序）,默认按最后更新章节排序  


###### 4.4.2 获得讨论帖首页信息（首楼，及首页的回帖）
http://127.0.0.1:8000/api/thread/1  
方法：GET  
授权：可选登陆与否，视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容）  
按讨论帖格式，返回id=1（id可以更换成其他数字）的讨论帖首页信息     ：thread,channel,tags,posts,pagination
视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容，只有符合要求的用户才能获得相关信息）  

###### 4.4.2.1 获得书籍首页信息（首楼，章节列表）
http://127.0.0.1:8000/api/book/1  
方法：GET  
授权：可选登陆与否，视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容）  
按书籍格式，返回id=1（id可以更换成其他数字）的书籍首页信息    。thread,channel,tags,chapters（章节）,volumns（分卷）,pagination  
视登陆与否返回不同结果（只有登陆后返回内容才包含边缘内容，只有符合要求的用户才能获得相关信息）  

#### 4.5 创建信息
###### 4.5.1 建立thread
http://127.0.0.1:8000/api/thread  
方法：POST  
授权：必须登陆  
必填项：  
channel(numeric) 数字，必须为自己有权限编辑的channel。这一项不填写的话，因为不能判断是给哪个channel新建thread，默认显示为“未授权”而被拒绝。  
title(string)：讨论帖标题  
brief(string)：讨论贴简介  
body(string)：首楼内容  
选填项：  
no_reply（anything）：是否不允许其他人在本楼回复，如果本项存在，则不允许回复。  
use_markdown(anything):是否使用markdown，如果本项存在，则保存为使用markdown。  
use_indentation(anything):是否使用段首缩进，如果本项存在，则保存为使用段首缩进。  
is_bianyuan（anything）：是否属于边缘内容，如果本项存在，则具体内容不被游客看到。（前端应控制，如果是thread选择bianyuan，简介以“午夜场”开头，并且提示用户本站关于午夜场的规则要求）  
is_not_public（anything）：是否属于私密内容，如果本项存在，则本楼整体隐藏。（前端应控制只有创立books才提交这个值）  

###### 4.5.1.1 修改thread
http://127.0.0.1:8000/api/thread/{thread}
方法：PUT|PATCH
授权：必须登陆,且用户必须是创建thread的用户
选填项：
title(string)：讨论帖标题  
brief(string)：讨论贴简介  
body(string)：首楼内容   
no_reply（anything）：是否不允许其他人在本楼回复，如果本项存在，则不允许回复。  
use_markdown(anything):是否使用markdown，如果本项存在，则回帖保存为使用markdown。  
use_indentation(anything):是否使用段首缩进，如果本项存在，则回帖保存为使用段首缩进。  
is_bianyuan（anything）：是否属于边缘内容，如果本项存在，则回帖不被游客看到。（前端应控制，如果是thread选择bianyuan，简介以“午夜场”开头，并且提示用户本站关于午夜场的规则要求）  
is_not_public（anything）：是否属于私密内容，如果本项存在，则本楼整体隐藏。（前端应控制只有创立books才提交这个值）

###### 4.5.1.2 给thread批量修改sync对应的tag
http://127.0.0.1:8000/api/thread/{thread}/synctags
方法：POST
授权：必须登陆,且用户必须是创建thread的用户
必填项：tags(array)用户希望增减的所有tag列表

###### 4.5.2 建立post
http://127.0.0.1:8000/api/thread/1/post
method: POST  
授权：必须登陆, 必须能够访问这个thread  
必填项：  
body(string):回帖正文  
preview(string):回帖摘要，由前端剪裁好提供，不得超过50字
选填项：  
is_anonymous(anything):是否匿名，如果本项存在，且本channel属于可以匿名，则回帖存储为匿名。   
majia(string):马甲 仅当存在“is_anonymous”的时候才保存马甲内容。  
reply_to_post_id(number):回复对象的post_id。前端务必检查好，这个post需要同时属于这个thread  
use_markdown(anything):是否使用markdown，如果本项存在，则回帖保存为使用markdown。  
use_indentation(anything):是否使用段首缩进，如果本项存在，则回帖保存为使用段首缩进。  
is_bianyuan（anything）：是否属于边缘内容，如果是，则回帖不被游客看到。

###### 4.5.2.1 修改post
http://127.0.0.1:8000/api/thread/1/post/1
method: PATCH  
授权：必须登陆, 必须能够访问这个thread，thread属于能够修改的channel内
选填项：  
body(string):回帖正文  
preview(string):回帖摘要，由前端剪裁好提供，不得超过50字
is_anonymous(anything):是否匿名，如果本项存在，且本channel属于可以匿名，则回帖存储为匿名。  
use_markdown(anything):是否使用markdown，如果本项存在，则回帖保存为使用markdown。  
use_indentation(anything):是否使用段首缩进，如果本项存在，则回帖保存为使用段首缩进。  

###### 4.5.3 建立recommendation (书籍推荐)
http://127.0.0.1:8000/api/recommendation
方法：POST  
授权：必须登陆,必须具有editor或senior_editor或admin身份  
必填项：  
thread(number):必须具有能够检索到的一个被推荐thread  
brief(string):必须具有一句话推荐简介  
type(string):'short'/'long'/'topic' 必须是下面array中的一项  
**注意事项**
（一个thread&&type组合，它只能有一个推荐。也就是说，一个书籍最多只有一个短推——可以再有长推）  
选填项:  
body(string):长推的话，在这里写入长推推荐语  
users(array of integers):e.g.[1,2,3] 这个推荐语的作者。书籍推荐语允许合作完成。

###### 4.5.3.2 审阅/修改 recommendation (书籍推荐的审阅)
http://127.0.0.1:8000/api/recommendation
方法：PATCH
授权：必须登陆,必须是自己或senior_editor或admin身份  
选填项:     
brief(string):必须具有一句话推荐简介    
body(string):长推的话，在这里写入长推推荐语  
is_public(bool):是否公开（不公开的话，其他人不能在书籍下看见）这个信息必须是senior_editor/admin才能改变，也就是说，书籍推荐在editor建立之后，需senior_editor审阅再转公开  
is_past(bool):是否属于往期推荐（影响首页显示情况）这个信息必须是senior_editor/admin才能改变，也就是说，书籍推荐需senior_editor审阅之后转公开  


###### 4.5.4.1 新建chapter  
http://127.0.0.1:8000/api/thread/{thread_id}/chapter  
方法：POST  
授权：必须登陆，需是自己创建的thread  
必填项：  
title(string)章节标题
brief(string)章节概要（如果用户不输入，前端自动节选body的一部分片段作为概要）
body(string)章节内容
选填项：
annotation(string):作者有话说/章节注释
annotation_infront(anything):如果出现，将作者有话说放在最前面


###### 4.5.4.2 更新chapter
http://127.0.0.1:8000/api/thread/{thread_id}/chapter/{chapter_id}
方法：PUT
授权：必须登陆，需是自己创建的thread
选填项
title(string)章节标题
brief(string)章节概要（如果用户不输入，前端自动节选body的一部分片段作为概要）
body(string)章节内容
annotation(string):作者有话说/章节注释
annotation_infront(anything):如果出现，将作者有话说放在最前面
