/**
 * Wrap classnames
 * 
 * Example: classnames('classname1', 'classname2', undefined, {'classname3': true}, {'classname4': false});
 */
export function classnames (firstName:any, ...args) {
  let cln:string[] = [];
  addToClassname(cln, firstName);
  for (let i = 0; i < args.length; i ++) {
    addToClassname(cln, args[i]);
  }
  return cln.join(' ');
}

function addToClassname (cln:string[], v:any) {
  if (v) {
    if (typeof v === 'object') {
      const keys = Object.keys(v);
      for (let i = 0; i < keys.length; i ++) {
        const _v = v[keys[i]];
        if (_v) {
          cln.push(_v);
        }
      }
    } else {
      cln.push(v.toString());
    }
  }
}