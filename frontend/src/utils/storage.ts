import { Themes } from '../view/theme/theme';
import { ResData } from '../config/api';

type Timestamp = number;
export type FilterDataType<T> = {
  updated_at:number;
  list:T[];
  selectedList:number[];
};

export interface Storage {
  auth:{
    token:string,
    username:string,
    userId:number,
  };
  theme:string;
  tag:FilterDataType<ResData.Tag>;
  channel:FilterDataType<ResData.Channel>;
  bianyuan:FilterDataType<{id:number, name:string}>;
}

export function allocStorage () : Storage {
  return {
    auth:{
      token:'',
      username:'',
      userId:-1,
    },
    theme: Themes.light,
    tag: {
      updated_at: 0,
      list: [],
      selectedList: [],
    },
    channel: {
      updated_at: 0,
      list: [],
      selectedList: [],
    },
    bianyuan: {
      updated_at: 0,
      list: [],
      selectedList: [],
    },
  };
}

export function saveStorage<K extends keyof Storage> (key:K, value:Storage[K]) {
  try {
    localStorage.setItem(key, JSON.stringify(value));
  } catch (e) {
    console.error(`saving storage failed ${key}:${value}`);
  }
}

export function loadStorage<K extends keyof Storage> (key:K) : Storage[K] {
  try {
    const data = localStorage.getItem(key);
    if (data) {
      const res = JSON.parse(data);
      return res;
    }
  } catch (e) {
    console.error('load storage failed with key ' + key);
  }
  return allocStorage()[key];
}

export function clearStorage<K extends keyof Storage> (key:K) {
  const clearState = allocStorage()[key];
  saveStorage(key, clearState);
}