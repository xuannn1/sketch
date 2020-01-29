import { History } from '.';
import { loadStorage, saveStorage, clearStorage } from '../utils/storage';

export class User {
  private history:History;

  public isLogin:boolean = false;
  public name:string = '';
  public id:number = -1;
  public token:string = '';

  constructor (history:History) {
    this.history = history;

    const auth = loadStorage('auth');
    if (auth.userId != -1) {
      this.isLogin = true;
      this.name = auth.username;
      this.id = auth.userId;
      this.token = auth.token;
    }
  }

  public login(name:string, id:number, token:string) {
    this.isLogin = true;
    this.name = name;
    this.id = id;
    this.token = token;
  }

  public isAdmin () : boolean {
    // fixme:
    return true;
  }

  public isLoggedIn () : boolean {
    return this.isLogin;
  }

  public logout () {
    clearStorage('auth');
    this.isLogin = false;
    this.name = '';
    this.id = -1;
    this.token = '';
    this.history.push('/');
  }
}