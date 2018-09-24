import { DB } from "./db";

export class Core {
    public db:DB;

    constructor () {
        this.db = new DB();
    }
}