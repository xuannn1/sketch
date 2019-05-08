/**
 * Wrap classnames
 * 
 * Example: classnames('classname1', 'classname2', undefined, {'classname3': true}, {'classname4': false});
 */
export function classnames (...args) {
  let cln:string[] = [];
  for (let i = 0; i < args.length; i ++) {
    if (args[i]) {
      if (typeof args[i] === 'object') {
        const keys = Object.keys(args[i]);
        for (let j = 0; j < keys.length; j ++) {
          if (args[i][keys[j]]) {
            cln.push(keys[j]);
          }
        }
      } else {
        cln.push('' + args[i]);
      }
    }
  }
  return cln.join(' ');
}