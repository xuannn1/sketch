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
  messages = '/messages',
  dialogue = '/messages/pm/:uid',
  personalMessages = '/message/pm',
  publicNotice = '/messages/publicnotice',

  // other
  tags = '/tags',
  search = '/search',
}