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

# 前端目录

- `frontend`
  - `src` 源码目录
  - `dst` 生成后的代码目录, 由webpack生成, 一般需要在ide搜索目录中排除出去
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
    - `pc` 电脑端
      - `index.tsx` 初始化、入口文件
      - `content.tsx` 路由文件
    - `index.tsx` 页面组件入口文件, 负责做一些公共(mobile和pc)的初始化处理
  - `index.tsx` 前端入口文件
