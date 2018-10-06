import { Quote } from "./data-types";

export class DB {
    private host:string;
    private protocol:string;

    constructor() {
        this.protocol = 'http';
        this.host = 'sosad.fun'; //fixme:
    }

    private async request (uri:string) {
        try {
            const res = await fetch(`${this.protocol}://${this.host}/${uri}`);
            return res;
        } catch (e) {
            console.log('Fetch Error: ' + e);
            return null;
        }
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