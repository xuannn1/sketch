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
        ctx.response.message = JSON.stringify(resData);
    } else {
        const errorMsg = 'server: cannot find ' + reqPath;
        console.log(errorMsg);
        ctx.response.message = errorMsg;
    }
});

app.listen(PORT);
console.log(`Server is listening on http://${ip.address()}:${PORT}`);