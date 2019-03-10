import { DB } from "./db";
import { User } from "./user";
import createHistory from 'history/createBrowserHistory';
import { History, UnregisterCallback } from 'history';
import { EventBus } from '../utils/events';
import * as _ from 'lodash/core';
import { Tag } from "./tag";
const debounce = require('lodash/debounce');

export type History = History;

export class Core {
    public db:DB;
    public user:User;
    public tag:Tag;
    public history:History;
    public unlistenHistory:UnregisterCallback;
    public windowResizeEvent:EventBus<void>;

    constructor () {
        this.history = createHistory();
        const location = this.history.location;
        this.unlistenHistory = this.history.listen((location, action) => {
            console.log(action, location.pathname, location.state);
        });

        this.tag = new Tag();
        this.db = new DB(this.history);
        this.user = new User(this.db, this.history);
        this.windowResizeEvent = new EventBus();
        window.addEventListener('resize', debounce(() => {
            this.windowResizeEvent.notify(undefined);
        }));
    }
}