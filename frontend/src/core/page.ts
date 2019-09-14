import { History, UnregisterCallback } from 'history';

export class Page {
    constructor (
        private _history:History,
    ) { }

    private _go (path:string) {
        this._history.push(path);
        this._history.goForward();
    }

    public channelTag (channelId:number, tagId:number) {
        this._go(`/threads/?channels=[${channelId}]&tags=[${tagId}]`);
    }
    public thread (threadId:number) {
        this._go(`/thread/${threadId}`);
    }

    public user (userId:number) {
        this._go(`/user/${userId}`);
    }
}