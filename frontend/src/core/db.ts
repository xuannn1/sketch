import { History } from '.';
import { APIPost, APIGet } from '../config/api';

export class DB {
    private host:string;
    private port:number;
    private protocol:string;
    private APIPREFIX = 'api';

    constructor (history:History) {
        this.protocol = 'http';
        // this.host = 'sosad.fun'; //fixme:
        this.host = 'localhost'; // for test
        this.port = 3001; // for test
    }

    public async post<Path extends keyof APIPost> (_path:Path, data:APIPost[Path]['req']) : Promise<APIPost[Path]['res']|null> {
        try {
            const url = `${this.protocol}://${this.APIPREFIX}/${this.host}:${this.port}${_path}`;

            console.log('post: ', url);
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
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

            const url = `${this.protocol}://${this.APIPREFIX}/${this.host}:${this.port}${_path}`;
            console.log('get: ' + url);
            const response = await fetch(url, {
                method: 'GET',
                headers: {

                },
            });
            const result = response.json();
            return result;
        } catch (e) {
            console.error('GET error: ' + e);
            return null;
        }
    }

    public async resetPassword (email:string) {
        return await this.post('/resetPwd', {email});
    }

    public getLogo () { //fixme:
        return '';
    } 

    public search (type:string, value:string, tongrenCP:string) {
        return ''; // fixme:
    }
}

function parseURL (url:string, query:{}) {
    let res = url;
    const pathMathches = url.match(/:\w+/g);
    for (const match in pathMathches) {
        const key = match.substr(1);
        res = res.replace(match, query[key]);
    }
    return res;
}