const Koa = require('koa');
const ip = require('ip');
const bodyParser = require('koa-bodyparser');

const PORT = 3001;

const config = {
    '/example': (req) => ({data: 'this is an example msg', code: 1}),
    '/login': (req) => {
        if (req.body.email === 'test@email.com' && req.body.pwd === '123456') {
            return {
                code: 1,
                data: 'valid user',
            };
        } else {
            return {
                code: 0,
                data: 'invalid user',
            }
        }
    },
    '/register': (req) => {
        return { code: 1, data: '' };
    },
    '/homeRecommendation': (req) => {
        return {
            code: 1,
            data: {
                cards: [
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                    {title: '死者苏生', content: '适合空气充斥着生活困顿、辞职待业、前途茫茫等大人的烦恼的失眠的深夜。', thread: 1, recommendation: 1},
                ],
                long: {title: '长评推荐《浪潮》', content: '孟小满跳楼自尽后，学校里就他的死因流传了两种截然不同的说法……魔幻现实笔法下的灰色青春校园，真假痛欲与人性："他们抛弃一切，在迷狂的浪潮中前进。"', thread: 2, recommendation: 2},
            },
        }
    }
}

const app = new Koa();
app.use(bodyParser({
    detectJSON: function (ctx) {
        return /\.json$/i.test(ctx.path);
    }
}));
app.use(async (ctx) => {
    const reqPath = ctx.request.path;
    console.log(reqPath);
    const reqHandler = config[reqPath];
    console.log('receive: ' + reqPath);
    if (reqHandler) {
        ctx.response.body = JSON.stringify(reqHandler(ctx.request));
        ctx.response.set('Content-Type', 'application/json');
        ctx.status = 200;
    } else {
        const errorMsg = 'server: cannot find ' + reqPath;
        console.log(errorMsg);
        ctx.response.body = errorMsg;
        ctx.status = 400;
    }
    ctx.response.set('Access-Control-Allow-Origin', '*');
    ctx.response.set('Access-Control-Allow-Methods', 'GET,HEAD,OPTIONS,POST,PUT');
    ctx.response.set('Access-Control-Allow-Headers', 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
});

app.listen(PORT);
console.log(`Server is listening on http://${ip.address()}:${PORT}`);