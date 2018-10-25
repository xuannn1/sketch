import { DB } from "./db";
import { User } from "./user";
import createHistory from 'history/createBrowserHistory';
import { History, UnregisterCallback } from 'history';

export type History = History;

export class Core {
    public db:DB;
    public user:User;
    public history:History;
    public unlistenHistory:UnregisterCallback;

    constructor () {
        this.history = createHistory();
        const location = this.history.location;
        this.unlistenHistory = this.history.listen((location, action) => {
            console.log(action, location.pathname, location.state);
        });

        this.db = new DB(this.history);
        this.user = new User(this.db, this.history);
    }
}