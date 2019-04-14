import { History } from '.';
import { APIPost, APIGet, APIPut, APIPatch, ResData, ReqData, Increments } from '../config/api';
import { parsePath, URLQuery } from '../utils/url';
import { loadStorage } from '../utils/storage';

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

    private async _fetch (
        path:string,
        reqInit:RequestInit,
        query?:URLQuery) {
            const headers = Object.assign({}, this.commonOption.headers, reqInit['headers']||{});
            const options = Object.assign({}, this.commonOption, reqInit, {headers});
            const token = loadStorage('token');
            if (token) {
                options.headers!['Authorization'] = `Bearer ${token}`;
            }
            let _path = path;
            if (query) {
                _path = parsePath(path, query);
            }
            const url = `${this.protocol}://${this.host}:${this.port}${this.API_PREFIX}${_path}`;
            console.log(options.method, url, options.body);

            try {
                const response = await fetch(url, options);
                const result = await response.json();
                if (!result.code || !result.data) {
                    console.log('Response type error:', result);
                    return null;
                }
                return result as {code:number, data:{[name:string]:any}};
            } catch (e) {
                console.error(e);
                return null;
            }
    }

    private async _get<T extends {[name:string]:any}> ( path:string, defaultResult:T, query?:URLQuery) {
        const res = await this._fetch( path, {method: 'GET'}, query);
        if (res && res.code === 200) {
            return res.data as T; 
        }
        return defaultResult;
    }

    private async _post (path:string, req?:{[key:string]:any}) {
        let body = '{}';
        if (req) {
            try {
                body = JSON.stringify(req);
            } catch (e) {
                console.error('JSON.stringfy error:', req);
                return null;
            }
        }
        const res = await this._fetch(path, {method: 'POST', body});
        if (res && res.code === 200) {
            return {
                success: true,
                data: res.data,
            };
        }
        return null;
    }

    private async _patch (path:string) {
        // fixme:
        return await this._fetch(path, {method: 'PATCH'});
    }

    private async _put (path:string) {
        // fixme:
        return await this._fetch(path, {method: 'PUT'});
    }

    public getPageHome () {
        return this._get('/', {
            quotes: [] as ResData.Quote[],
            recent_added_chapter_books: [] as ResData.Thread[],
            recent_responded_books: [] as ResData.Thread[],
            recent_responded_threads: [] as ResData.Thread[],
            recent_statuses: [] as ResData.Status[],
        });
    }

    public getAllTags () {
        return this._get('/config/allTags', {
            tags: [] as ResData.Tag[],
        });
    }

    public getNoTongrenTags () {
        return this._get('/config/noTongrenTags', {
            tags: [] as ResData.Tag[],
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
        return this._get(
            '/thread', 
            {
                threads:[] as ResData.Thread[],
                paginate: ResData.allocChapter(),
            },
            query,
        );
    }

    public getPageHomeThread () {
        return this._get('/homethread', {} as {
            [idx:string]:{
                channel:ResData.Channel;
                threads:ResData.Thread[];
            }
        });
    }

    public getThread (id:number, page?:number) {
        const query = page ? {page} : undefined;
        return this._get(
            `/thread/${id}`,
            {
                thread: ResData.allocThread(),
                posts: [] as ResData.Post[],
                paginate: ResData.allocThreadPaginate(),
            },
            query,
        );
    }

    public getPageHomeBook () {
        return this._get('/homebook', {
            recent_long_recommendations: [] as ResData.Post[],
            recent_short_recommendations: [] as ResData.Post[],
            random_short_recommendations: [] as ResData.Post[],
            recent_custom_short_recommendations: [] as ResData.Post[],
            recent_custom_long_recommendations: [] as ResData.Post[],
            recent_added_chapter_books: [] as ResData.Thread[],
            recent_responded_books: [] as ResData.Thread[],
            highest_jifen_books: [] as ResData.Thread[],
            most_collected_books: [] as ResData.Thread[],
        });
    }

    public getBook (id:number, page?:number) {
        const query = page ? { page } : undefined;
        const init = {
            thread: ResData.allocThread(),
            chapters: [] as ResData.Post[],
            volumns: [] as ResData.Volumn[],
            paginate: ResData.allocThreadPaginate(),
            most_upvoted: ResData.allocPost(),
            top_review: null as null|ResData.Post,
        };
        return this._get('/book/' + id, init, query);
    }

    public getCollection (query?:{
        user_id?:number;
        withType?:ReqData.Collection.Type;
        ordered?:ReqData.Thread.ordered;
    }) {
        const init = {
            threads: [] as ResData.Thread[],
            paginate: ResData.allocThreadPaginate(),
        };
        return this._get('/collection', init, query);
    }

    public getUserMessage (id:number, query:{
        withStyle:ReqData.Message.style;
        chatWith?:Increments;
        ordered?:ReqData.Message.ordered;
        read?:ReqData.Message.read;
    }) {
        const init = {
            messages: [] as ResData.Message[],
            paginate: ResData.allocThreadPaginate(),
            style: ReqData.Message.style.sendbox,
        };
        return this._get(`/user/${id}/message`, init, query);
    }

    public getStatus () {
        //fixme:
        return this._get( '/status', {});
    }

    public register (req:{
        name:string;
        password:string;
        email:string;
    }) {
        // fixme: custom fetch
    }

    public login (req:{
        email:string;
        password:string;
    }) {
        // fixme: custom fetch
        return this._post('/login', req);
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
}