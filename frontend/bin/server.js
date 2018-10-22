const Koa = require('koa');
const ip = require('ip');

const PORT = 3001;

const config = {
    '/example': {data:'this is an example msg', code: 1},
}

const app = new Koa();
app.use(async (ctx) => {
    const reqPath = ctx.request.path;
    const resData = config[reqPath];
    console.log('receive: ' + reqPath);
    if (resData) {
        ctx.response.body = JSON.stringify(resData);
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