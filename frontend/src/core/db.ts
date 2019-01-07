import { History } from '.';
import { APIPost, APIGet } from '../config/api';
import { parsePath, URLQuery } from '../utils/url';

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

    private _parseURL (_path:string, obj?:URLQuery) {
        const path = obj ? parsePath(_path, obj) : _path;
        return `${this.protocol}://${this.host}:${this.port}${this.API_PREFIX}${path}`;
    }

    public async post<Path extends keyof APIPost> (_path:Path, data:APIPost[Path]['req']) : Promise<APIPost[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, data);

            console.log('post: ', url);
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'content-type': 'application/json',
                },
                mode: 'cors',
                credentials: 'same-origin',
                cache: 'no-cache',
                body: JSON.stringify(data || {}),
            });
            const result = response.json();
            return result
        } catch (e) {
            console.error('Post Error: ' + e);
            return null;
        }
    }

    public async get<Path extends keyof APIGet> (_path:Path, query:APIGet[Path]['req']) : Promise<APIGet[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, query);
            console.log('get: ' + url);
            const response = await fetch(url, {
                method: 'GET',
                mode: 'cors',
                credentials: 'same-origin',
                cache: 'no-cache',
            });
            const result = response.json();
            return result;
        } catch (e) {
            console.error('GET error: ' + e);
            return null;
        }
    }

    public async resetPassword (email:string) {
        // return await this.post('/resetPwd', {email});
    }

    public getLogo () { //fixme:
        return '';
    } 

    public search (type:string, value:string, tongrenCP:string) {
        return ''; // fixme:
    }
}