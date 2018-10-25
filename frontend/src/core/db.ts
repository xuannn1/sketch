import { Quote } from "./data-types";
import { History } from '.';

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

    public async request (uri:string) {
        try {
            if (uri[0] === '/') {
                uri = uri.substr(1);
            }
            const url = `${this.protocol}://${this.host}:${this.port}/${uri}`;
            console.log('request: ', url);
            const res = await fetch(url, {
                method: 'post',
            });
            return res.json();
        } catch (e) {
            console.error('Fetch Error: ' + e);
            return null;
        }
    }

    public async resetPassword (email:string) {
        return await this.request('/resetPwd');
    }

    public getLogo () { //fixme:
        return '';
    } 

    public search (type:string, value:string, tongrenCP:string) {
        return ''; // fixme:
    }

    public getQuotes () : Quote[] {
        const testData = {
            id: 0,
            quote: 'this is a test quote',
            anonymous: 0,
            majia: 'majia',
            notsad: 0,
            approved: 1,
            reviewed: 2,
            xianyu: 3,
            user_name: 'username',
        };

        return (new Array(4)).fill(testData);
    }
}