const Koa = require('koa');
const ip = require('ip');

const PORT = 3001;

const config = {
    '/example': (req) => ({data: 'this is an example msg', code: 1}),
    '/login': (req) => {
        if (req.query.email === 'test@email.com' && req.query.pwd === '123456') {
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
}

const app = new Koa();
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