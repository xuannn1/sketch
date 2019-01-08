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
                cache: 'no-cache',
                mode: 'cors',
                body: JSON.stringify(data || {}),
            });
            const result = (await response.json()) as APIPost[Path]['res'];
            if (result.code === 200) {
                return result;
            } else {
                console.log('Response Error:', result);
                return null;
            }
        } catch (e) {
            console.error('Post Error: ', e);
            return null;
        }
    }

    public async get<Path extends keyof APIGet> (_path:Path, query:APIGet[Path]['req']) : Promise<APIGet[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, query);
            console.log('get: ' + url);
            const response = await fetch(url, {
                method: 'GET',
                cache: 'no-cache',
            });
            const result = (await response.json()) as APIGet[Path]['res'];
            if (result.code === 200) {
                return result;
            } else {
                console.log('Response Error:', result);
                return null;
            }
        } catch (e) {
            console.error('GET error: ', e);
            return null;
        }
    }

    public async resetPassword (email:string) {
        // return await this.post('/', {email});
    }

    public getLogo () { //fixme:
        return '';
    } 

    public search (type:string, value:string, tongrenCP:string) {
        return ''; // fixme:
    }
}