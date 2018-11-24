import { History } from '.';
import { ResponseList } from '../config/response-data';

export class DB {
    private host:string;
    private port:number;
    private protocol:string;

    constructor (history:History) {
        this.protocol = 'http';
        // this.host = 'sosad.fun'; //fixme:
        this.host = 'localhost'; // for test
        this.port = 3001; // for test
    }

    public async request<U extends keyof ResponseList> (_path:U, data?:{}) : Promise<ResponseList[U]|null> {
        try {
            const url = `${this.protocol}://${this.host}:${this.port}${_path}`;
            console.log('request: ', url);
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
            console.error('Fetch Error: ' + e);
            return null;
        }
    }

    public async resetPassword (email:string) {
        return await this.request('/resetPwd', {email});
    }

    public getLogo () { //fixme:
        return '';
    } 

    public search (type:string, value:string, tongrenCP:string) {
        return ''; // fixme:
    }
}