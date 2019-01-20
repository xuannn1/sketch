import { DB } from "./db";
import { History } from '.';
import { loadStorage, saveStorage } from "../utils/storage";

export class User {
    private db:DB;
    private history:History;
    
    private isLogin = false;
    private name = '';

    constructor (db:DB, history:History) {
        this.db = db;
        this.history = history;

        const token = loadStorage('token');
        if (token) {
            this.isLogin = true;
        }
    }

    public async login (email:string, pwd:string) {
        //todo:
        const res = await this.db.post(`/login`, {email, password: pwd});
        if (!res) { return false; }
        this.isLogin = true;
        this.history.push('/');
        saveStorage('token', res.data.token);
        return true;
    }

    public async register (spec:{
        email:string,
        password:string,
        name:string,
    }) {
        const res = await this.db.post(`/register`, spec);
        if (!res) { return false; }
        this.isLogin = true;
        this.history.push('/');
        saveStorage('token', res.data.token);
        return true;
    }

    public isAdmin () : boolean {
        // fixme:
        return true;
    }

    public isLoggedIn () : boolean {
        return this.isLogin;
    }

    public logout () {
        saveStorage('token', '');
        this.isLogin = false;
        this.history.push('/');
    }
}