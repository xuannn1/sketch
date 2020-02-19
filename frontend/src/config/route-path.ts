export enum RoutePath {
  home = '/',
  createQuote = '/createquote',
  suggestion = '/suggestion',
  library = '/library',

  // forum
  forum = '/thread_index',
  chapter = '/book/:bid/chapter/:cid',

  // user
  user = '/user',
  login = '/login',
  register = '/register',

  // collection
  collection = '/collection',

  // status
  status = '/status/all',
  statusCollection = '/status/collection',

  // messages
  personalMessages = '/messages/pm/all',
  dialogue = '/messages/pm/dialogue/:uid',
  publicNotice = '/messages/publicnotice',
  messages = '/messages/activity',

  // other
  tags = '/tags',
  search = '/search',
}