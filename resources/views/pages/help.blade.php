@extends('layouts.default')
@section('title', '帮助')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1">
                                <h2>帮助</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="help-navigation h5">
                            <ol>
                                <li><a href="#help-1" class="h4">网站界面</a></li>
                                <ul>
                                    <li><a href="#help-1-1">账户注册，激活，忘记密码</a></li>
                                    <li><a href="#help-1-2">积分，咸鱼，剩饭，丧点</a></li>
                                    <li><a href="#help-1-3">顶部导航栏；签到；怎么取消消息提醒</a></li>
                                    <li><a href="#help-1-4">首页格局</a></li>
                                    <li><a href="#help-1-5">底部辅助页面</a></li>
                                </ul>
                                <li><a href="#help-2" class="h4">读者看文</a></li>
                                <ul>
                                    <li><a href="#help-2-1">文库淘文：怎么只看我想看的类型/性向/进度？</a></li>
                                    <li><a href="#help-2-2">收藏：第一时间知道太太更新了没（还是在摸鱼..）</a></li>
                                    <li><a href="#help-2-3">论坛各个板块说明</a></li>
                                    <li><a href="#help-2-4">看文时怎么表达对太太的爱</a></li>
                                </ul>
                                <li><a href="#help-3" class="h4">作者发文</a></li>
                                <ul>
                                    <li><a href="#help-3-1">发文入口</a></li>
                                    <li><a href="#help-3-2">主题贴操作</a></li>
                                </ul>
                                <li><a href="#help-4" class="h4">常见问题</a></li>
                                <ul>
                                    <li><a href="#help-4-1">你们怎么既有“原创小说”版面，又有“文库”版面，这么混乱的啊？！</a></li>
                                    <li><a href="#help-4-2">听说这里的编辑器识别的是BBCode，什么是BBCode，怎么使用？</a></li>
                                    <li><a href="#help-4-3">什么是 Markdown 格式，怎么使用？</a></li>
                                    <li><a href="#help-4-4">页面崩溃，丢失数据怎么办？急，在线等！</a></li>
                                    <li><a href="#help-4-5">什么是段首缩进，怎么操作？</a></li>
                                    <li><a href="#help-4-6">你们什么时候制作app？网上那个app是你们的吗，为什么logo不一样？</a></li>
                                    <li><a href="#help-4-7">如何支持你们？</a></li>
                                    <li><a href="#help-4-8">更多问题？戳我</a></li>
                                </ul>
                                <li><a href="#help-5" class="h4">5. 当前设置</a></li>
                                <ul>
                                    <li><a href="#help-5-1">5.1 当前页面数据</a></li>
                                    <li><a href="#help-5-2">5.2 升级标准</a></li>
                                    <li><a href="#help-5-3">5.3 不同等级的权限</a></li>
                                    <li><a href="#help-5-4">5.4 昨日统计数据</a></li>
                                    <li><a href="#help-5-5">5.5 页面屏蔽字符</a></li>
                                    <ul>
                                        <li><a href="#help-5-5-1">5.5.1 出现在标题/简介/章节名中会被隐藏的词汇</a></li>
                                        <li><a href="#help-5-5-2">5.5.2 出现在书名中会被隐藏的特殊字符</a></li>
                                    </ul>
                                </ul>
                            </ol>
                        </div>
                        <br>
                        <div class="help-explanation">
                            <div id="help-1">
                                <h2>1 网站界面</h4>
                                    <div id="help-1-1">
                                        <h4>1.1 账户注册，激活，忘记密码</h4>
                                        <ul>
                                            <li>注册：本论坛目前采用邀请码注册制度。邀请码获取请关注相关宣传信息。</li>
                                            <li>激活： 由于邮件服务不足，邮件激活功能暂时关闭，目前不需手工激活，注册即可登陆。</li>
                                            <li>忘记密码： 在登陆页面点选“忘记密码”，输入自己的邮箱，即可接收重置密码邮件，点开其中的链接重置密码。</li>
                                            <li>其他疑难，可以在<em><a href="http://sosad.fun/threads/49">版务管理专版</a></em>跟帖，或者微博管理员公共账户“废文网大内总管”咨询。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-1-2">
                                        <h4>1.2 积分，咸鱼，剩饭，丧点</h4>
                                        <div class="main-text indentation">
                                            <p>以上是本站特色的虚拟积分系统。发文、发帖、回帖、点评、签到、参加活动……都能增加这些积分。积分影响等级，等级影响到网站一小部分的功能使用，比如发送私信等。咸鱼和剩饭能够对作者进行奖励。本站大部分功能对全部用户开放，不需要对积分获取过于执着。</p>
                                            <em><a href="#help-2-4">(前往2.4中看文互动，明确这些虚拟货币的用途)</a></em>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-1-3">
                                        <h4>1.3 顶部导航栏：签到；怎么取消消息提醒</h4>
                                        <ul>
                                            <li>签到按钮（登陆可见）：在导航栏最顶，有一个红色的<code>“我要签到”</code>按键。为了避免用户熬夜签到，签到按钮设置在<u>北京时间早上7-9点</u>开始出现，直到用户完成签到后再次消失等待下一天。签到有签到奖励，签到可以让用户升级，连续签到达到一定日期之后，奖励也会增加。</li>
                                            <li>动态：用户动态及用户列表。</li>
                                            <li>文库：可以按照文章类型淘文的地方。</li>
                                            <li>论坛：本站其他讨论。</li>
                                            <li>收藏（登陆可见）：<em> <a href="#help-2-2">戳我跳转到收藏架的详细用法</a></em></li>
                                            <li>用户专区（登陆可见）：这里显示登陆用户的用户名。</li>
                                            <ul>
                                                <li>个人主页：查看自己的所有状态、已发表的书籍和主题贴（包括隐藏、匿名的）、发表的长评、给出的赞，还能看自己关注的作者，和自己的粉丝情况。</li>
                                                <li>编辑资料：修改密码，高级用户还可以关联马甲。</li>
                                                <li>我要发文：在这里新建原创/同人文章，具体的后面会展开。</li>
                                                <li>消息中心：查看收到的私信、回帖和赞。</li>
                                                <li>用户的各色马甲：可以一键切换到自己已经关联的马甲账户，避免重复输入密码。狡兔三窟，诚不我欺也。</li>
                                                <li>退出：退出登录。</li>
                                            </ul>
                                            <li>登陆（游客可见）：都懂的。</li>
                                            <li>注册（游客可见）：都懂的。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-1-4">
                                        <h4>1.4 首页格局</h4>
                                        <ul>
                                            <li>临时搜索（登陆可见）：可以搜索用户/主题贴/同人原著+CP。由于资源有限，暂时设置成一个用户1分钟内只能搜索一次。</li>
                                            <li>题头：本站精神风貌的代表呈现部分</li>
                                            <li>小微博：有些难识别，上面写着“今天你丧了吗...”，边上有一个“发布”键。这里可以发布当前心情微博。</li>
                                            <li>论坛主要板块：按序排布，后述。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-1-5">
                                        <h4>1.5 底部辅助链接</h4>
                                        <ul>
                                            <li>帮助页面：就是您现在浏览的页面。</li>
                                            <li>关于页面：本站概况和相关原则。注册用户默认遵守这些规则，请务必仔细查看。</li>
                                            <li>Github：本站源代码。</li>
                                            <li>管理记录：管理员进行操作的记录在此公示。</li>
                                        </ul>
                                    </div>
                                </div>
                                <br>
                                <div id="help-2">
                                    <h2>2. 读者看文</h2>
                                    <div id="help-2-1">
                                        <h4>2.1 文库淘文：怎么只看我想看的类型/性向/进度？</h4>
                                        <ul>
                                            <li><code>a. 单项淘文：</code>在文库区，点击任何关于“书籍进度（连载/完结/暂停）”，“书籍篇幅（短篇中篇长篇）”，“书籍原创性（原创/同人）”，“书籍性向（BL/GL/BG/GB/无CP/混合性向/其他性向）”，“书籍类型（科幻/奇幻/现代/古代...）”，“书籍标签（美强/强强/正剧/小甜饼/暗黑/HE...）”以上任何超链接，点击之后，都可以跳转进入筛选页面。浏览器收藏该页面，下次就可以直接淘这类文章。</li>
                                            <li><code>b. 复合淘文（积极排除不想看的类型）：</code>点击导航栏“文库”按钮即可进入文库淘文。就在导航栏之下，有筛选功能。这里提供复合筛选，并且一键进入该筛选特定的网页。读者可以收藏筛选结果所在的网页，日后直接一键进入淘文。</li>
                                            <li>关于文库排序：目前文库按照最后更新章节时间倒序排列（此章节必须达到一定字数要求，数据见本页最下的“实时数据”展示）。以后会推出更多排序方式。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-2-2">
                                        <h4>2.2 收藏：第一时间知道太太更新了没（还是在摸鱼..）</h4>
                                        <ul>
                                            <li>文章收藏：在对应文章主页点选“收藏”按钮，就能收藏该文章。收藏后，<code>文章更新</code>将会发送提示。<br> 可以通过“收藏->整理”，来针对性管理每一篇文章的收藏情况。可以单独选择删除收藏，或者屏蔽对该文章的更新提醒。</li>
                                            <li>主题贴收藏：主题贴收藏和文章收藏操作一致，点击“收藏”即可。主题贴收藏后，会提示其他人对这一主题进行<code>讨论回帖</code>的情况。</li>
                                            <li>动态收藏：这里可以查看您所关注用户的小微博更新。</li>
                                            <li>收藏单收藏：可以编辑自己的收藏单，新建自己的收藏单，也可以看自己关注的他人收藏单的更新情况。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-2-3">
                                        <h4>2.3 论坛板块说明</h4>
                                        <ul>
                                            <li>原创小说：原创的虚构性文学作品。</li>
                                            <li>同人小说：在其他作品基础上衍生创作的虚构性文学作品。</li>
                                            <li>作业专区（隐藏）：报名作业的同学进行写文练习活动的地方。</li>
                                            <li>读写交流：和写文读文有关的心得体悟等。</li>
                                            <li>日常闲聊：水区，和站里的咸鱼天南海北地发丧。</li>
                                            <li>随笔：放置个人非虚构的各类作品。</li>
                                            <li>站务管理：管理员发放通知，宣传更新情况，进行咨询使用答疑，管理删帖/转区等各项事务。</li>
                                            <li>违规举报：小摩擦、小矛盾，一般情况下的违反版规，回帖不友善，观察到发文不规范（标题、简介、格式、分区不对），来这里汇报。一言不合要关小黑屋的哦。</li>
                                            <li>投诉仲裁：专门十分严重、严肃的争议.</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-2-4">
                                        <h4>2.4 看文互动</h4>
                                        <ul>
                                            <li>咸鱼：一种昂贵恶臭的虚拟物，扔咸鱼能顶帖。</li>
                                            <li>剩饭：一种廉价广谱的虚拟物，没什么卵用，但能让太太知道有人在看文章。</li>
                                            <li>回帖：顶帖，通过文字记录走心的感受，让作者知道你的爱。</li>
                                            <li>点评：在文案留下一两句话，所有的人一眼都能看到，当然包括太太。</li>
                                            <li>赞赏：在单独章节下点击向上的小手，太太能够收到闪烁的黄色提醒</li>
                                            <li>收藏：将本文加入收藏夹，获得更新提醒，收藏数字是文章质量的一大标志。（收藏数字是实时的，会掉收藏）</li>
                                        </ul>
                                    </div>
                                </div>
                                <br>
                                <div id="help-3">
                                    <h2>3 作者发文</h2>
                                    <div id="help-3-1">
                                        <h4>3.1 发文入口</h4>
                                        <ul>
                                            <li>从右上角“用户专区->我要发文“进入，按顺序点选填写相应信息即可发文。</li>
                                            <li>发文时如有延迟，请勿反复点选“发布”键。</li>
                                            <li>请区分文案和正文，<u>正文请点击<code>发新章节</code>进行发布</u>，不要放在文案内。标题和文案请符合规范。</li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div id="help-3-2">
                                        <h4>3.2 作者能做的操作</h4>
                                        <ul>
                                            <li>是否公开书籍：如果不勾选，则书籍不对外开放，只能从个人主页进入，可以作为<code>私下存文</code>的渠道。管理员发现帖子标题等处不规范，也会将该项取消勾选（也就是隐藏处理），作者修改好后可以自行公开，恢复帖子的阅读。</li>
                                            <li>是否允许跟帖：如果不勾选，其他读者只能看文不能评论。如果遇到文下争议，来不及等待管理员处理，此操作可以应急。（之后可以悠哉悠哉地去管理版面登记，举报不友好评论，惩罚ky分子）</li>
                                            <li>是否允许讨论帖下载：勾选后，其他读者能够以支付作者咸鱼和剩饭为代价，实时下载包含书评在内、按时间顺序排列的全文txt文件作为存档。本选项默认勾选。</li>
                                            <li>是否允许书籍下载：勾选后，高等级读者能够以支付作者<em>更多</em>咸鱼和剩饭为代价，实时下载不包含书评在内，只含章节正文的纯净书籍txt文件。本选项默认不勾选。</li>
                                        </ul>
                                    </div>
                                </div>
                                <br>
                                <div id="help-4">
                                    <h2>4. 常见问题</h2>
                                    <div id="help-4-1">
                                        <h4>4.1 你们怎么既有“原创小说”版面，又有“文库”版面，这么混乱的啊？！</h4>
                                        <div class="indentation main-text">
                                            <p>您好，这正是本站特色所在。各大文学网站往往是论坛或文库。其中论坛的优点在于讨论迅速、热烈、反馈多，缺点在于淘文困难、长文阅读体验差。而文库的优点在于淘文方便、具有阅读器，缺点则是用户追文留评的冲动大幅减少。<code>本站恰是观察到这些特点，试图合并“文库”和“论坛”，营造一个既具有论坛讨论气氛，又具有淘文便利性的平台</code>。无论是“原创小说”还是“同人小说”，都和文库关联在一起，本质上是一个东西。</p>
                                            <p>在本站浏览的书籍，都具有两种形式，读者可以随时一键切换。第一种是“论坛讨论模式”，所有帖子按照时间顺序排列，方便读者一路追下来，营造良好的追文气氛。第二种是“文库阅读模式”，在书籍封面显示目录，读者一章一章地阅读，可以进行“上一章”、“下一章”的跳转，追文时发布的帖子不会丢失，而是智能“跟随”在对应章节的下面。</p>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-4-2">
                                        <h4>4.2 听说这里的编辑器识别的是BBCode，什么是BBCode，怎么使用？</h4>
                                        <div class="indentation main-text">
                                            <em><a href="http://www.xys.org/bbcode.html">参考资料：网络上关于BBCode的介绍</a></em>
                                            <p>BBCode，这是网站目前使用的文字格式系统（虽然，是一个魔改版）。如果您的帖子需要”斜体“、”加粗“、高亮、列表、插图、插入超链接...等等格式，您可以通过点勾“显示编辑器”使得编辑器出现，并在对应的文本框里点击按钮应用格式，再点击“预览”查看应用效果。原始BBcode不识别普通空行，但本站是识别的。</p><p> 此外，本站会清理除<code>&lt;br&gt;</code>之外的、段落间所有的冗余空行。简单地说，作者如果希望实现“多空行”的显示效果，将<code>&lt;br&gt;</code>插入到需要空行的地方即可，插入多少个，就多空几行。</p>
                                        </div>
                                    </div>
                                    <code>编辑器正在施工中，如果出现显示问题，请稍安勿躁，前往版务区<em><a href="http://sosad.fun/threads/49">跟帖</a></em> 呼唤管理员解决问题。</code>
                                    <br>
                                    <br>
                                    <div id="help-4-3">
                                        <h4>4.3 什么是 Markdown 格式，怎么使用？</h4>
                                        <div class="indentation main-text">
                                            <p>这是网站目前不再推荐使用的格式系统（虽然，仍然是一个魔改版）。 Markdown 语法要求两个空格或者两个回车作为换行符号，但本站处理成普通回车即可换行，并且会清理除<code>&lt;br&gt;</code>之外的、段落间所有的冗余空行，其余格式。如果对此语法没有深入了解的兴趣，请千万不要勾选本项。</p>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-4-4">
                                        <h4>4.4 页面崩溃，丢失数据怎么办？急，在线等！</h4>
                                        <div class="indentation main-text">
                                            <p>用户登录后，所有大文本框下均有<code>”恢复数据“</code>按键，是随时向服务器提交您之前输入的数据的，点选它即可恢复上次输入的文本文件。连续点击“恢复数据”可以从多个恢复数据档案中切换<code>如果不小心误按，再点一次即可撤销。</code></p>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-4-5">
                                        <h4>4.5 什么是段首缩进，怎么操作？</h4>
                                        <div class="indentation main-text">
                                            <p>勾选之后能够在<u><em>现有文字基础上</em></u>自动在段首<code>空两格</code>。不选的话，就不自动空格（缩进）。默认是勾选的，如果作者的文字本身<code>已有空格</code>，可以取消勾选此项，获得更好看的显示效果。譬如说，太太发现自己的文章<u><em>明明应该“空两格”</em></u>，甚至在自己的word文档里空格都是对的，复制过来却空了四格，请记得<code>取消勾选</code>"段首锁进"这一项哦！</p>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-4-6">
                                        <h4>你们什么时候制作app？网上那个app是你们的吗，为什么logo不一样？</h4>
                                        <div class="indentation main-text">
                                            <p>本站官方APP仍在建设中，预计19年暑期后上线。</p>
                                            <p>目前网络上存在的是第三方制作的套壳app，需要的同学可以自行搜索获取。本站<code>不负责</code>第三方app的任何安全、技术支持、维护、美容等问题。本站不向用户收取任何使用费用，第三方app也不得收取任何附加费用/进行商业盈利行为。简而言之，请各位新老咸鱼<code>擦亮双眼</code>，根据自己的需要合理选择。</p>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="help-4-7">
                                        <h4>4.7 如何支持你们？</h4>
                                        <div class="indentation main-text">
                                            <p>感谢各位朋友好意，本站暂不需要经济支持，感恩笔芯！不过，欢迎有经验、有兴趣、有时间的朋友联系加入我们的开发工作，比如说：具有 mysql 数据库使用经验、laravel 开发经验、app 开发经验、熟悉PHP编程语言...如果感兴趣，联系方式按照程序员的惯例...相信你们一定是找得到的！</p>
                                            <p>大家积极产粮，友善看文，热爱科学，天天向丧，就是对我们最好的支持！</p>
                                        </div>
                                    </div>
                                    <div id="help-4-8">
                                        <h4>4.8 更多问题？戳我</h4>
                                        <div class="indentation main-text">
                                            <li><a href="https://sosad.fun/threads/9756">《网站使用FAQ汇总楼》</a></li>
                                            <li><a href="https://sosad.fun/threads/49">《网站使用答疑楼》</a></li>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div id="help-5">
                                    <h2>5. 当前设置</h2>
                                    <ul>
                                        <div id="help-5-1">
                                            <h4>5.1 当前页面数据</h4>
                                            <ul>
                                                <li>当前在线注册用户数：{{ $users_online }}人</li>
                                                <li>信息每页显示：{{ $data['items_per_page'] }}个</li>
                                                <li>信息每分区显示：{{ $data['items_per_part'] }}个</li>
                                                <li>目录每页显示：{{ $data['index_per_page'] }}个</li>
                                                <li>目录每分区显示：{{ $data['index_per_part'] }}个</li>
                                                <li>长评标准：{{ $data['longcomment_lenth'] }}字</li>
                                                <li>新章节的更新字数必须达到{{ $data['update_min'] }}字，才能计入本书“最后更新”的排名数据（顶帖）</li>
                                            </ul>
                                        </div>
                                        <br>
                                        <div id="help-5-2">
                                            <h4>5.2 升级标准</h4>
                                            <ul>
                                                @foreach($data['level_up'] as $level=>$level_requirement)
                                                <li>等级：{{$level}}</li>
                                                <ul>
                                                    <li>所需积分： {{$level_requirement['experience_points']}}，所需咸鱼： {{$level_requirement['xianyu']}}，所需丧点： {{$level_requirement['sangdian']}}</li>
                                                </ul>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <br>
                                        <div id="help-5-3">
                                            <h4>5.3 不同等级的权限</h4>
                                            <div class="main-text indentation">
                                                <p>用户<code>签到</code>时会检查是否符合升级条件，符合则自动升级。</p>
                                                <p>普通互动，比如回帖、点评、签到、投掷咸鱼剩饭、赞赏，都能增加积分、盐度（也就是经验）。签到送数值不等的咸鱼、剩饭、盐度、积分，连续签到次数越多，每次签到奖励的页越多，有时会出现额外奖励（些许剩饭咸鱼，额度很低，不要纠结）。更新书籍章节、发表主题贴、发表长评，可奖励数值不等的丧点。作业活动也会奖励丧点。丧点可用于参与站内读写活动，荣誉性较高，没有其他实际作用。用户的等级也影响：可以创建的收藏清单的数量，可以发送陌生人私信的数量，可以关联的马甲账户数量。</p>
                                                <p><strong>1级</strong>能下载讨论帖，看边限内容，发文。</p>
                                                <p><strong>2级</strong>能回帖，能（需作者开放下载）下载“讨论贴格式”的含评论书籍，能在用户的问题箱提问，能新建讨论主题帖，能发动态。</p>
                                                <p><strong>3级</strong>能（需作者开放下载）下载“脱水”书籍；能<code>关联马甲号</code>（从编辑资料进入），关联后能免密码登陆马甲，能创建收藏清单，能看边限目录，筛选边限tag。</p></p>
                                                <p>请珍惜账户，不要<code>重复频繁</code>发布<u>无意义水贴</u>试图升级，尤其不要在文下发布和文章内容无关的求升级水贴内容，一经发现将作等级清零处理。也不要伪发文意图增加积分，24h无内容的，书籍会自动被程序隐藏。</p>
                                                <p></p>
                                                <p>用户日常签到时能获得和自己等级相等的陌生人私信限额，用于给未关注自己的用户发送私信（对方可选择屏蔽陌生人私信）。互相关注用户可以发送无限量私信</p>
                                            </div>
                                        </div>
                                        <br>
                                        <div id="help-5-4">
                                            <h4>5.4 昨日网站数据汇总</h4>
                                            <ul>
                                                <li>昨日新注册用户：{{ $webstat['new_users'] }}人</li>
                                                <li>昨日签到人数：{{ $webstat['qiandaos'] }}个</li>
                                                <li>昨日发帖总数：{{ $webstat['posts'] }}个</li>
                                                <li>昨日新增章节数：{{ $webstat['posts_maintext'] }}个</li>
                                                <li>昨日新增回复数：{{ $webstat['posts_reply'] }}个</li>
                                                <li>昨日点评数：{{ $webstat['post_comments'] }}个</li>
                                            </ul>
                                        </div>
                                        <br>
                                        @if(Auth::check())
                                        <div id="help-5-5">
                                            <h4>5.5 页面屏蔽字符</h4>
                                            <div id="help-5-5-1">
                                                <h5>5.5.1 出现在标题/简介/章节名中会被隐藏的词汇（用‘|’隔开）：</h5><h6>
                                                    <img src="/img/forbidden_words.png" alt="forbidden_words">
                                                </h6>
                                            </div>
                                            <br>
                                            <div id="help-5-5-2">
                                                <h5>5.5.2 出现在书名中会被隐藏的特殊字符（用‘|’隔开）：</h5><h6>
                                                    {{ $data['word_filter']['not_in_title'] }}
                                                </h6>
                                            </div>
                                        </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-md btn-danger sosad-button" onclick="topFunction()" id="myBtn" title="Go to top">回到页面顶部</button>
        </div>
    </div>
    <script>
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

@stop
