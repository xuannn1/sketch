import { History } from 'history';
import { ReqData, Increments, API } from '../config/api';
import { parsePath, URLQuery } from '../utils/url';
import { saveStorage } from '../utils/storage';
import { ErrorMsg, ErrorCodeKeys } from '../config/error';
import { User } from './user';

type JSONType = {[name:string]:any}|string;
type FetchOptions = {
  query?:URLQuery,
  body?:JSONType,
  pathInsert?:(number|string)[],
  errorMsg?:{[code:string]:string},
  errorCodes?:ErrorCodeKeys[],
};

export class DB {
  private user:User;
  private history:History;
  private host:string;
  private port:number;
  private protocol:string;
  private API_PREFIX = '/api';

  constructor (user:User, history:History) {
    this.user = user;
    this.history = history;
    this.protocol = 'http';
    // this.host = 'sosad.fun'; //fixme:
    this.host = '34.70.54.149';      // 'localhost' for test
    this.port = 8000; // for test
  }
  private _handleError (code:number|string, msg:string) {
    return new Error(JSON.stringify({
      code,
      msg,
    }));
  }
  private commonOption:RequestInit = {
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Accept': 'application/json, text/plain, */*',
    },
    mode: 'cors',
  };
  private async _fetch<T extends JSONType> (path:string, reqInit:RequestInit, spec:FetchOptions = {}) {
    const headers = Object.assign({}, this.commonOption.headers, reqInit['headers'] || {});
    const options = Object.assign({}, this.commonOption, reqInit, {headers});
    const token = this.user.token;
    if (token) {
      options.headers!['Authorization'] = `Bearer ${token}`;
    }
    let _path = path;
    if (spec.pathInsert) {
      for (let i = 0; i < spec.pathInsert.length; i ++) {
        _path = _path.replace(`$${i}`, '' + spec.pathInsert[i]);
      }
    }
    if (spec.query) {
      _path = parsePath(_path, spec.query);
    }
    if (spec.body) {
      try {
        options.body = JSON.stringify(spec.body);
      } catch (e) {
        throw this._handleError(0, ErrorMsg.JSONParseError);
      }
    }

    const url = `${this.protocol}://${this.host}:${this.port}${this.API_PREFIX}${_path}`;
    console.log(options.method, url, options.body);

    const errorMsgKeys = Object.keys(spec.errorMsg || {});

    const handleErrorCodes = (code:ErrorCodeKeys) => {
      if (spec.errorMsg && errorMsgKeys.indexOf('' + code) >= 0) {
        throw this._handleError(code, spec.errorMsg[code]);
      }
      if (spec.errorCodes && spec.errorCodes.indexOf(code) >= 0) {
        throw this._handleError(code, ErrorMsg[code]);
      }
    };

    try {
      const response = await fetch(url, options);
      const result = await response.json();
      if (!result.code || !result.data) {
        console.error('response:', result);
        throw this._handleError(500, ErrorMsg.JSONParseError);
      }
      if (result.code === 200) {
        return result.data as T;
      }
      throw handleErrorCodes(result.code);
    } catch (e) {
      throw this._handleError(501, ErrorMsg.FetchError);
    }
  }
  private _get<Path extends keyof API.Get> (path:Path, ops:FetchOptions = {}) {
    return this._fetch<API.Get[Path]>(path, {method: 'GET'}, ops);
  }
  private _post<Path extends keyof API.Post> (path:Path, ops:FetchOptions = {}) {
    return this._fetch<API.Post[Path]>(path, {method: 'POST', headers:{'Content-Type': 'application/json'}}, ops);
  }
  private _patch<Path extends keyof API.Patch> (path:Path, ops:FetchOptions = {}) {
    return this._fetch<API.Patch[Path]>(path, {method: 'PATCH'}, ops);
  }
  // private _put<Path extends keyof API.Put> (path:Path, ops:FetchOptions = {}) {
  //   return this._fetch<API.Put[Path]>(path, {method: 'PUT'}, ops);
  // }
  private _delete<Path extends keyof API.Delete> (path:Path, ops:FetchOptions = {}) {
    return this._fetch<API.Delete[Path]>(path, {method: 'DELETE'}, ops);
  }

  // page
  public getPageHome () {
    return this._get('/');
  }
  public getPageHomeThread () {
    return this._get('/homethread');
  }
  public getPageHomeBook () {
    return this._get('/homebook');
  }

  // follow system
  public followUser (userId:number) {
    return this._post(`/user/$0/follow`, {
      pathInsert: [userId],
      errorCodes: [401],
      errorMsg: {
        403: '不能关注自己',
        404: '指定用户不存在',
        412: '已经关注，无需重复关注',
      },
    });
  }
  public unFollowUser (userId:number) {
    return this._delete(`/user/$0/follow`, {
      pathInsert: [userId],
      errorCodes: [401],
      errorMsg: {
        403: '不能取关自己',
        404: '不能取关不存在用户',
        412: '已经未关注了，不能重复取关',
      },
    });
  }
  public updateFollowStatus (userId:number, keep_updated:boolean) {
    return this._patch(`/user/$0/follow`, {
      pathInsert: [userId],
      body: {keep_updated},
      errorCodes: [401, 403, 404, 412],
    });
  }
  public getFollowingIndex (userId:number) {
    return this._get(`/user/$0/following`, {
      pathInsert: [userId],
      errorCodes: [401],
    });
  }
  public getFollowingStatuses (userId:number) {
    return this._get(`/user/$0/followingStatuses`, {
      pathInsert: [userId],
      errorCodes: [401],
    });
  }
  public getFollowers (userId:number) {
    return this._get(`/user/$0/follower`, {
      pathInsert: [userId],
      errorCodes: [401],
    });
  }

  // Message System
  public sendMessage (toUserId:number, content:string) {
    return this._post('/message', {
      body: {
        sendTo: toUserId,
        body: content,
      },
      errorCodes: [403],
    });
  }
  public sendGroupMessage (toUsers:number[], content:string) {
    return this._post('/groupmessage', {
      body: {
        sendTos: toUsers,
        body: content,
      },
      errorCodes: [403],
      errorMsg: {
        404: '未能找到全部对应的收信人',
      },
    });
  }
  public getMessages (
    query:{
      withStyle:ReqData.Message.style;
      chatWith?:Increments;
      ordered?:ReqData.Message.ordered;
      read?:ReqData.Message.read;
    },
    id:number = this.user.id,
  ) {
    return this._get(`/user/$0/message`, {
      pathInsert: [id],
      query,
    });
  }
  public getPublicNotice () {
    return this._get('/publicnotice', {
      errorCodes: [401],
    });
  }
  public sendPublicNotice (content:string) {
    return this._post('/publicnotice', {
      body: {
        body: content,
      },
      errorCodes: [403],
    });
  }

  // User Title System
  public getAllTitles () {
    return this._get('/config/titles');
  }
  public getUserTitles (userId:number) {
    return this._get(`/user/$0/title`, {
      pathInsert: [userId],
      errorCodes: [401],
    });
  }
  public updateTitleStatus (userId:number, titleId:number, status:ReqData.Title.status) {
    return this._patch(`/user/$0/title/$1`, {
      pathInsert: [userId, titleId],
      body: {
        options: status,
      },
      errorCodes: [401, 409],
    });
  }

  // Vote System
  public vote (type:ReqData.Vote.type, id:number, attitude:ReqData.Vote.attitude) {
    return this._post('/vote', {
      body: {
        votable_type: type,
        votable_id: id,
        attitude,
      },
      errorCodes: [401],
      errorMsg: {
        404: '未找到该投票对象',
        409: '不能重复投票或请先踩赞冲突',
      },
    });
  }
  public getVotes (type:ReqData.Vote.type, id:number, attitude?:ReqData.Vote.attitude) {
    return this._get('/vote', {
      query: {
        votable_type: type,
        votable_id: id,
        attitude,
      },
    });
  }
  public deleteVote (voteId:number) {
    return this._delete(`/vote/$0`);
  }

  // Thread System
  public getThreadList (query?:{
    channels?:number[],
    tags?:number[],
    excludeTag?:number[],
    withBianyuan?:ReqData.Thread.withBianyuan,
    ordered?:ReqData.Thread.ordered,
    withType?:ReqData.Thread.withType,
    page?:number;
  }) {
    return this._get('/thread', {
      query,
    });
  }
  public getThread (id:number, query?:{
    page?:number,
    ordered?:ReqData.Thread.ordered,
  }) {
    return this._get(`/thread/$0`, {
      pathInsert: [id],
      query,
    });
  }
  public getThreadPosts (threadId:number, query?:{
    withType?:ReqData.Post.withType,
    withComponent?:ReqData.Post.withComponent,
    userOnly?:number, // user id
    withReplyTo?:number, // post id
    ordered?:ReqData.Post.ordered,
  }) {
    return this._get(`/thread/$0/post`, {
      pathInsert: [threadId],
      query,
    });
  }
  public turnToPost (threadId:number, postId:number) {
    return this._patch(`/thread/$0/post/$1/turnToPost`, {
      pathInsert: [threadId, postId],
    });
  }
  public updateThreadTags (threadId:number, tags:number[]) {
    return this._patch(`/thread/$0/synctags`, {
      pathInsert: [threadId],
      body: {
        tags,
      },
    });
  }
  // public publishThread (req:{
  //     title:string;
  //     brief:string;
  //     body:string;
  //     no_reply?:boolean;
  //     use_markdown?:boolean;
  //     use_indentation?:boolean;
  //     is_bianyuan?:boolean;
  //     is_not_public?:boolean;
  // }) {
  //     return this._post( '/thread', req);
  // }

  // public addPostToThread (threadId:number, post:{
  //     body:string;
  //     brief:string;
  //     is_anonymous?:boolean;
  //     majia?:string;
  //     reply_id?:number;
  //     use_markdown?:boolean;
  //     use_indentation?:boolean;
  //     is_bianyuan?:boolean;
  // }) {
  //     return this._post(`/thread/${threadId}/post`, post);
  // }

  // Book System
  public addChapterToThread (threadId:number, chapter:{
    title:string;
    brief:string;
    body:string;
    annotation?:string;
    annotation_infront?:boolean;
  }) {
    return this._post(`/thread/$0/chapter`, {
      pathInsert: [threadId],
      body: chapter,
    });
  }
  public getBook (id:number, page?:number) {
    const query = page ? { page } : undefined;
    return this._get('/book/$0', {
      pathInsert: [id],
      query,
    });
  }

  // Collection System
  public collectThread (threadId:number) {
    return this._post(`/thread/$0/collect`, {
      pathInsert: [threadId],
    });
  }
  public getCollection (withType?:ReqData.Collection.type) {
    return this._get('/collection', {
      query: {
        withType,
      },
    });
  }

  // User System
  public async register (name:string, password:string, email:string, backTo?:string) {
    const res = await this._post('/register', {
      query:{name, password, email},
      errorMsg: {
        422: '用户名/密码/邮箱格式错误',
      },
    });

    if (!res) { return false; }
    this.user.login(res.name, res.id, res.token);
    saveStorage('auth', {token: res.token, username: res.name, userId: res.id});
    backTo ? this.history.push(backTo) : this.history.push('/');
    return true;
  }
  public async login (email:string, password:string, backTo?:string) {
    const res = await this._post('/login', {
      query: {
        email,
        password,
      },
      errorMsg: {
        401: '用户名/密码错误',
      },
    });
    if (!res) { return false; }
    this.user.login(res.name, res.id, res.token);
    saveStorage('auth', {token: res.token, username: res.name, userId: res.id});
    backTo ? this.history.push(backTo) : this.history.push('/');
    return true;
  }
  public resetPassword (email:string) {
    // fixme:
    return new Promise<boolean>((resolve) => resolve(true));
  }

  // Status System

  // others
  public addQuote (body:{
    body:string;
    is_anonymous?:boolean;
    majia?:string;
  }) {
    return this._post('/quote', body);
  }
  public getNoTongrenTags () {
    // fixme:
    return new Promise<[]>((resolve) => resolve([]));
  }
}