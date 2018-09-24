import { DB } from "./db";

export class Handler {
    public db:DB;

    constructor () {
        this.db = new DB();
    }
}