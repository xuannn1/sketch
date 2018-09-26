import { DB } from "./db";
import { User } from "./user";

export class Core {
    public db:DB;
    public user:User;

    constructor () {
        this.db = new DB();
        this.user = new User(this.db);
    }
}