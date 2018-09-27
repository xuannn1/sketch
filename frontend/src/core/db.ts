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
}