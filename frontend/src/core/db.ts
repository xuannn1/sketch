import { History } from '.';
import { APIPost, APIGet, APIPut, APIPatch } from '../config/api';
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

    private _parseURL (_path:string, obj?:URLQuery) {
        const path = obj ? parsePath(_path, obj) : _path;
        return `${this.protocol}://${this.host}:${this.port}${this.API_PREFIX}${path}`;
    }

    public async post<Path extends keyof APIPost> (_path:Path, data:APIPost[Path]['req']) : Promise<APIPost[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, data);

            console.log('post: ', url);
            const response = await fetch(url, this.genRequestInit({
                method: 'POST',
                headers: {
                    'content-type': 'application/json',
                },
                body: JSON.stringify(data || {}),
            }));
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
            const response = await fetch(url, this.genRequestInit());
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

    public async put<Path extends keyof APIPut> (_path:Path, query:APIPut[Path]['req']) : Promise<APIPut[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, query);
            console.log('put: ' + url);
            const response = await fetch(url, this.genRequestInit());
            const result = (await response.json()) as APIPut[Path]['res'];
            if (result.code === 200) {
                return result;
            } else {
                console.log('Response Error:', result);
                return null;
            }
        } catch (e) {
            console.error('PUT error: ', e);
            return null;
        } 
    }

    public async patch<Path extends keyof APIPatch> (_path:Path, query:APIPatch[Path]['req']) : Promise<APIPatch[Path]['res']|null> {
        try {
            const url = this._parseURL(_path, query);
            console.log('Patch: ' + url);
            const response = await fetch(url, this.genRequestInit());
            const result = (await response.json()) as APIPatch[Path]['res'];
            if (result.code === 200) {
                return result;
            } else {
                console.log('Response Error:', result);
                return null;
            }
        } catch (e) {
            console.error('Patch error: ', e);
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

    public getAuth () {
        const token = loadStorage('token');
        if (token) {
            return `Bearer ${loadStorage('token')}`;
        }
        return undefined;
    }

    public genRequestInit (init:RequestInit = {}) {
        const reqInit = {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
            mode: 'cors',
        } as RequestInit;
        const token = loadStorage('token');
        if (token) {
            reqInit.headers!['Authorization'] = `Bearer ${token}`;
        }

        for (const key in init) {
            if (key === 'headers') {
                Object.assign(reqInit.headers, init[key]);
            } else {
                reqInit[key] = init[key];
            }
        }
        
        return reqInit;
    }
}