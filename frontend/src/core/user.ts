import { History } from '.';
import { loadStorage, saveStorage } from '../utils/storage';

export class User {
  private history:History;
  
  public isLogin = false;
  public name = '';

  constructor (history:History) {
    this.history = history;

    const token = loadStorage('token');
    if (token) {
      this.isLogin = true;
    }
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