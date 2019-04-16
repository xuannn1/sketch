import { History } from '.';
import { ResData, ReqData, Increments } from '../config/api';
import { parsePath, URLQuery } from '../utils/url';
import { loadStorage } from '../utils/storage';
import { ErrorMsg, ErrorCodeKeys } from '../config/error';

type JSONType = {[name:string]:any};
type FetchOptions<T extends JSONType> = {
    initData?:T,
    query?:URLQuery,
    body?:JSONType,
    errorMsg?:{[code:string]:string},
    errorCodes?:ErrorCodeKeys[],
}

export class DB {
    private host:string;
    private port:number;
    private protocol:string;
    private API_PREFIX = '/api';

    constructor (history:History) {
        this.protocol = 'http';
        // this.host = 'sosad.fun'; //fixme:
        this.host = 'localhost'; // for test
        this.port = 8000; // for test
    }

    private commonOption:RequestInit = {
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json, text/plain, */*',
        },
        mode: 'cors',
    };

    private async _fetch<T extends JSONType> (path:string, reqInit:RequestInit, spec:FetchOptions<T> = {}) {
        const headers = Object.assign({}, this.commonOption.headers, reqInit['headers']||{});
        const options = Object.assign({}, this.commonOption, reqInit, {headers});
        const token = loadStorage('token');
        if (token) {
            options.headers!['Authorization'] = `Bearer ${token}`;
        }
        let _path = path;
        if (spec.query) {
            _path = parsePath(path, spec.query);
        }
        if (spec.body) {
            try {
                options.body = JSON.stringify(options.body);
            } catch (e) {
                console.error(ErrorMsg.JSONParseError, e);
            }
            return;
        }
        const url = `${this.protocol}://${this.host}:${this.port}${this.API_PREFIX}${_path}`;
        console.log(options.method, url, options.body);

        const errorMsgKeys = Object.keys(spec.errorMsg || {});
        try {
            const response = await fetch(url, options);
            const result = await response.json();
            if (!result.code || !result.data) {
                console.error(ErrorMsg.JSONParseError, result);
                return;
            }
            if (result.code === 200) {
                return result.data as T;
            }
            return handleErrorCodes(result.code);
        } catch (e) {
            console.error(ErrorMsg.FetchError, e);
            return Promise.reject({code: 501, msg: ErrorMsg.FetchError});
        }

        function handleErrorCodes (code:ErrorCodeKeys) {
            if (spec.errorMsg && errorMsgKeys.indexOf('' + code) >= 0) {
                return Promise.reject({code, msg: spec.errorMsg[code]});
            }
            if (spec.errorCodes && spec.errorCodes.indexOf(code) >= 0) {
                return Promise.reject({code, msg: ErrorMsg[code]});
            }
            return;
        }
    }

    private async _get<T extends JSONType> (path:string, ops:FetchOptions<T> = {}) {
        const res = await this._fetch(path, {method: 'GET'}, ops);
        if (res) { return res as T; }
        if (ops.initData) {
            return ops.initData;
        }
        return;
    }

    private _post<T extends JSONType> (path:string, ops:FetchOptions<T> = {}) {
        return this._fetch(path, {method: 'POST'}, ops);
    }
    private _patch<T extends JSONType> (path:string, ops:FetchOptions<T> = {}) {
        return this._fetch(path, {method: 'PATCH'}, ops);
    }
    private _put<T extends JSONType> (path:string, ops:FetchOptions<T> = {}) {
        return this._fetch(path, {method: 'PUT'}, ops);
    }
    private _delete<T extends JSONType> (path:string, ops:FetchOptions<T> = {}) {
        return this._fetch(path, {method: 'DELETE'}, ops);
    }

    // page fetch
    public getPageHome () {
        return this._get('/home', {
            initData: {
                quotes: [] as ResData.Quote[],
                recent_added_chapter_books: [] as ResData.Thread[],
                recent_responded_books: [] as ResData.Thread[],
                recent_responded_threads: [] as ResData.Thread[],
                recent_statuses: [] as ResData.Status[],
            },
        });
    }
    public getPageHomeThread () {
        return this._get('/homethread', {
            initData: {} as {
                [idx:string]:{
                    channel:ResData.Channel;
                    threads:ResData.Thread[];
                },
            },
        });
    }
    public getPageHomeBook () {
        return this._get('/homebook', {
            initData: {
                recent_long_recommendations: [] as ResData.Post[],
                recent_short_recommendations: [] as ResData.Post[],
                random_short_recommendations: [] as ResData.Post[],
                recent_custom_short_recommendations: [] as ResData.Post[],
                recent_custom_long_recommendations: [] as ResData.Post[],
                recent_added_chapter_books: [] as ResData.Thread[],
                recent_responded_books: [] as ResData.Thread[],
                highest_jifen_books: [] as ResData.Thread[],
                most_collected_books: [] as ResData.Thread[],
            }
        });
    }

    public getAllTags () {
        return this._get('/config/allTags', {
            initData: {
                tags: [] as ResData.Tag[],
            },
        });
    }

    public getNoTongrenTags () {
        return this._get('/config/noTongrenTags', {
            initData: {
                tags: [] as ResData.Tag[],
            },
        });
    }

    public getThreadList (query?:{
        channels?:number[],
        tags?:number[],
        excludeTag?:number[],
        withBianyuan?:ReqData.Thread.withBianyuan,
        ordered?:ReqData.Thread.ordered,
        withType?:ReqData.Thread.withType,
        page?:number;
    }) {
        return this._get( '/thread', {
            initData: {
                threads:[] as ResData.Thread[],
                paginate: ResData.allocChapter(),
            },
            query,
        });
    }

    public getThread (id:number, page?:number) {
        const query = page ? {page} : undefined;
        return this._get(
            `/thread/${id}`, {
            initData: {
                thread: ResData.allocThread(),
                posts: [] as ResData.Post[],
                paginate: ResData.allocThreadPaginate(),
            },
            query,
        });
    }

    public getBook (id:number, page?:number) {
        const query = page ? { page } : undefined;
        const initData = {
            thread: ResData.allocThread(),
            chapters: [] as ResData.Post[],
            volumns: [] as ResData.Volumn[],
            paginate: ResData.allocThreadPaginate(),
            most_upvoted: ResData.allocPost(),
            top_review: null as null|ResData.Post,
        };
        return this._get('/book/' + id, {
            initData,
            query,
        });
    }

    public getCollection (query?:{
        user_id?:number;
        withType?:ReqData.Collection.Type;
        ordered?:ReqData.Thread.ordered;
    }) {
        const initData = {
            threads: [] as ResData.Thread[],
            paginate: ResData.allocThreadPaginate(),
        };
        return this._get('/collection', {
            initData,
            query,
        });
    }

    public getUserMessage (id:number, query:{
        withStyle:ReqData.Message.style;
        chatWith?:Increments;
        ordered?:ReqData.Message.ordered;
        read?:ReqData.Message.read;
    }) {
        const initData = {
            messages: [] as ResData.Message[],
            paginate: ResData.allocThreadPaginate(),
            style: ReqData.Message.style.sendbox,
        };
        return this._get(`/user/${id}/message`, {
            initData,
            query,
        });
    }

    public getStatus () {
        //fixme:
        return this._get( '/status', {});
    }

    public register (body:{
        name:string;
        password:string;
        email:string;
    }) {
        return this._post('/register', {
            body,
            errorMsg: {
                422: '用户名/密码/邮箱格式错误',
            },
        });
    }

    public login (body:{
        email:string;
        password:string;
    }) {
        return this._post('/login', {
            body,
            errorMsg: {
                401: '用户名/密码错误',
            },
        });
    }

    public publishThread (req:{
        title:string;
        brief:string;
        body:string;
        no_reply?:boolean;
        use_markdown?:boolean;
        use_indentation?:boolean;
        is_bianyuan?:boolean;
        is_not_public?:boolean;
    }) {
        return this._post( '/thread', req);
    }

    public updateTagToThread (threadId:number, tags:number[]) {
        return this._post(`/thread/${threadId}`, {tags});
    }

    public addPostToThread (threadId:number, post:{
        body:string;
        brief:string;
        is_anonymous?:boolean;
        majia?:string;
        reply_id?:number;
        use_markdown?:boolean;
        use_indentation?:boolean;
        is_bianyuan?:boolean;
    }) {
        return this._post(`/thread/${threadId}/post`, post);
    }

    public addChapterToThread (threadId:number, chapter:{
        title:string;
        brief:string;
        body:string;
        annotation?:string;
        annotation_infront?:boolean;
    }) {
        return this._post(`/thread/${threadId}/chapter`, chapter);
    }

    public addRecommendation (req:{
        type:'short'|'long'|'topic';
        body?:string;
        users:number[];
    }) {
        return this._post('/recommendation', req);
    }

    public addQuote (req:{
        body:string;
        is_anonymous?:boolean;
        majia?:string;
    }) {
        return this._post('/quote', req);
    }

    // follow system
    public followUser (userId:number) {
        return this._post<{user:ResData.User}>(`/user/${userId}/follow`, {
            errorCodes: [401],
            errorMsg: {
                403: '不能关注自己',
                404: '指定用户不存在',
                412: '已经关注，无需重复关注',
            },
        });
    }
    public unFollowUser (userId:number) {
        return this._delete<{user:ResData.User}>(`/user/${userId}/follow`, {
            errorCodes: [401],
            errorMsg: {
                403: '不能取关自己',
                404: '不能取关不存在用户',
                412: '已经未关注了，不能重复取关',
            },
        });
    }
    public updateFollowStatus (userId:number, keep_updated:boolean) {
        return this._patch<ResData.User>(`/user/${userId}/follow`, {
            body: {keep_updated},
            errorCodes: [401, 403, 404, 412],
        })
    }
    public getFollowingIndex (userId:number) {
        const initData = {
            user: ResData.allocUser(),
            followings: [] as ResData.User[],
            paginate: ResData.allocThreadPaginate(),
        };
        return this._get(`/user/${userId}/following`, {
            initData, 
            errorCodes: [401],
        });
    }
    public getFollowingStatuses (userId:number) {
        const initData = {
            user: ResData.allocUser(),
            followingStatuses: [] as ResData.User[],
            paginate: ResData.allocThreadPaginate(),
        };
        return this._get(`/user/${userId}/followingStatuses`, {
            initData,
            errorCodes: [401],
        });
    }
    public getFollowers (userId:number) {
        const initData = {
            user: ResData.allocUser(),
            followers: [] as ResData.User[],
            paginate: ResData.allocThreadPaginate(),
        };
        return this._get(`/user/${userId}/follower`, {
            initData,
            errorCodes: [401],
        });
    }

    // Message System
}