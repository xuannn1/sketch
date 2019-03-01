export type URLQuery = {[key:string]:string|number|boolean|any[]|undefined};
export function parsePath (path:string, query:URLQuery) {
    let res = path;

    let obj = Object.assign({}, query);
    const matches = path.match(/:\w+/g);

    if (matches) {
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
    }

    const keys = Object.keys(obj);
    if (keys.length > 0) {
        res += '/?';
        for (let i = 0; i < keys.length; i ++) {
            if (i !== 0) { res += '&'; }
            const key = keys[i];
            const value = obj[key];
            if (value === undefined) { continue; } 
            if (value instanceof Array) {
                res += `${key}=[${value}]`
            } else {
                res += `${key}=${value}`;
            }
        }
    }

    return res;
}

// parse the match query to array type
export function parseArrayQuery (url:string, query:string) {
    try {
        const parser = new URL(url);
        const value = parser.searchParams.get(query);
        if (!value) {
            return undefined;
        }

        if (Number.isNaN(+value)) {
            const res = JSON.parse(value);
            if (res instanceof Array) {
                return res;
            }
            return undefined;
        }

        return [+value];
    } catch (e) {
        return undefined;
    }
}

export function addQuery (url:string, query:string, value:string|number) {
    const parser = new URL(url);
    if (parser.search) {
        const sameQuery = parser.searchParams.get(query);
        if (sameQuery) {
            return parser.origin + parser.pathname + parser.search.replace(`${query}=${sameQuery}`, `${query}=${value}`);
        }
        return parser.origin + parser.pathname + parser.search + '&' + query + '=' + value;
    }
    return parser.origin + parser.pathname + '?' + query + '=' + value;
}