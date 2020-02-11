import { DB } from './db';
import { loadStorage, saveStorage, Storage, FilterDataType } from '../utils/storage';
import { ResData } from '../config/api';

const EXPIRE_TIME_MS = 1000 * 3600 * 24;

class FilterHandler<T> {
  protected _db:DB;
  protected _selectedList:number[] = [];
  protected _list:T[] = [];

  private _saveData:(data:FilterDataType<T>) => void;
  private _loadData:() => Promise<FilterDataType<T>>;

  constructor (
    db:DB,
    loadData:() => Promise<FilterDataType<T>>,
    saveData:(data:FilterDataType<T>) => void,
  ) {
    this._db = db;
    this._saveData = saveData;
    this._loadData = loadData;
  }

  public async init () {
    const data = await this._loadData();
    this._selectedList = data.selectedList;
    this._list = data.list;
  }

  public get (id:number) {
    return this._list[id - 1];
  }

  public select (id:number) {
    const idx = this._selectedList.indexOf(id);
    if (idx < 0) {
      this._selectedList.push(id);
    } else {
      this._selectedList.splice(idx, 1);
    }
    this._saveData({
      updated_at: Date.now(),
      list: this._list,
      selectedList: this._selectedList,
    });
  }

  public isSelected (id:number) {
    return this._selectedList.indexOf(id) >= 0;
  }

  public getSelectedList () {
    return this._selectedList.slice();
  }

  public save () {
    this._saveData({
      updated_at: Date.now(),
      list: this._list,
      selectedList: this._selectedList,
    });
  }
}

export class TagHandler extends FilterHandler<ResData.Tag> {
  private _types:{[type:string]:ResData.Tag[]} = {};

  constructor (db:DB) {
    super(
      db,
      async () => {
        const data = loadStorage('tag');
        if (data.updated_at - Date.now() > EXPIRE_TIME_MS || !data.list.length) {
          const res = await db.getAllTags();
          const updatedData:FilterDataType<ResData.Tag> = {
            updated_at: Date.now(),
            list: res.tags,
            selectedList: data.selectedList,
          };
          saveStorage('tag', updatedData);
          return updatedData;
        }
        return data;
      },
      (data) => {
        saveStorage('tag', data);
      },
    );
  }

  private _parseTypes = () => {
    const types = this._types;
    this._list.forEach((tag) => {
      if (!types[tag.attributes.tag_type]) {
        types[tag.attributes.tag_type] = [];
      }
      types[tag.attributes.tag_type].push(tag);
    });
  }

  public getAllTagTypes () {
    const keys = Object.keys(this._types);
    if (!keys.length) {
      this._parseTypes();
    }
    return Object.keys(this._types);
  }
}

export class ChannelHandler extends FilterHandler<ResData.Channel> {
  constructor (db:DB) {
    super (
      db,
      async () => {
        const data = loadStorage('channel');
        if (data.updated_at - Date.now() > EXPIRE_TIME_MS) {
          const res = await db.getAllChannels();
          const updatedData = {
            updated_at: Date.now(),
            list: res.channels,
            selectedList: data.selectedList,
          };
          saveStorage('channel', updatedData);
          return updatedData;
        }
        return data;
      },
      (data) => {
        saveStorage('channel', data);
      },
    );
  }
}

export class BianyuanHandler extends FilterHandler<{id:number, name:string}> {
  constructor (db:DB) {
    super (
      db,
      async () => {
        return loadStorage('bianyuan');
      },
      (data) => {
        saveStorage('bianyuan', data);
      },
    );
  }
}