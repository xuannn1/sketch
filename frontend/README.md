# Startup
第一次拉下代码后需要初始化:
```
npm install
```

开发:
```
npm run start
```
之后修改前端代码, 浏览器窗口也会即时刷新.


生产环境打包:
```
npm run build
```

# 需要学习:

- React
- TypeScript
  - Basic Types
  - Variable Declarations
  - Interfaces
  - Classes
  - Functions
  - Enums
  - 进阶: Generics
- React-router 4.0
- ES6 async

# 前端目录结构

- `frontend`
  - `src` 源码目录
  - `dist` 生成后的代码目录, 由webpack生成, 一般需要在ide搜索目录中排除出去
  - `tsconfig.json` ts设置文件
  - `tslint.json` ts lint文件
  - `webpack.config.js` webpack设置文件
  - `yarn.lock` yarn包锁定文件
  - `package.json` node包管理文件
  - `index.html` 

- `frontend/src`
  - `config` 设置类, 如网站url, 如path, 等
  - `core` 所有控制组件
    - `index.ts` 负责初始化其他所有控制组件实例并提供一个统一的入口
    - `db.ts` 数据库操作相关
  - `utils` 其他常用function/class
  - `view` 页面渲染
    - `components` 小块的页面组件, 手机端和电脑端可共用的
    - `mobile` 手机端
      - `index.tsx` 入口文件
      - `navbar.tsx` 一级导航条
      - `home` 首页页面
      - `collection` 收藏页面
      - `notification` 通知页面
      - `status` 动态页面
      - `user` 用户页面
    - `pc` 电脑端
      - `index.tsx` 初始化、入口文件
      - `content.tsx` 路由文件
    - `index.tsx` 页面组件入口文件, 负责做一些公共(mobile和pc)的初始化处理
  - `index.tsx` 前端入口文件

# 前后端数据交互 (原ajax)

将想要测试的数据和对应路径添加到 `bin/server.js` 文件中:  

```js
const config = {
    '/example': {data:'this is an example msg', code: 1},
    // 可按照上面示范继续添加测试数据, data下可以放任意数据, 目前code = 1表示数据获取成功, code = 0表示数据获取失败
}
```

之后开一个新的终端页面开启测试服务器: `npm run server`

前端代码发送和获取数据范例:

```js
const data = core.db.request('example');
console.log(data); // {data:'this is an example msg', code: 1}
```

为了方便以后修改数据接口, 建议在 `src/core/db.ts` 文件中的 `class DB` 下, 添加新的方法来处理数据, 在react component中只调用该方法来获得返回数据.