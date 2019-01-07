export type URLQuery = {[key:string]:string|number|boolean|any[]|undefined};
export function parsePath (path:string, query:URLQuery) {
    let res = path;

    let obj = Object.assign({}, query);
    const matches = path.match(/:\w+/g);
    if (!matches) { return res; }

    for (const match of matches) {
        const key = match.substr(1);
        const value = obj[key];
        if (value && (
            typeof value === 'string' ||
            typeof value === 'number' ||
            typeof value === 'boolean'
        )) {
            res = res.replace(match, value.toString());
            delete obj[key];
        } else {
            res = res.replace(match, '');
        }
    }

    const keys = Object.keys(obj);
    if (keys.length > 0) {
        res += '/?';
        for (let i = 0; i < keys.length; i ++) {
            if (i !== 0) { res += '&'; }
            const key = keys[i];
            const value = obj[key];
            if (value instanceof Array) {
                res += `${key}=[${value}]`
            } else {
                res += `${key}=${value}`;
            }
        }
    }

    return res;
}