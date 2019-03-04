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
export function parseArrayQuery (url:string, query:string) : any[]|undefined {
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
            return parser.pathname + parser.search.replace(`${query}=${sameQuery}`, `${query}=${value}`);
        }
        return parser.pathname + parser.search + '&' + query + '=' + value;
    }
    return parser.pathname + '?' + query + '=' + value;
}

export function removeQuery (url:string, query:string) {
    const parser = new URL(url);
    const sameQuery = parser.searchParams.get(query);
    if (!sameQuery) { return parser.pathname + parser.search; }
    let replace = `${query}=${sameQuery}`.replace('[', '\\[').replace(']', '\\]');
    if (new RegExp('\\?' + replace, 'g').test(parser.search)) {
        replace = replace + '&?';
    } else {
        replace = '&' + replace;
    }
    const regex = new RegExp(replace, 'g');
    const search = parser.search.replace(regex, '');
    if (search === '?') {
        return parser.pathname;
    } else {
        return parser.pathname + search;
    }
}

export function addArrayQuery (url:string, query:string, value:string|number) {
    const parser = new URL(url);
    if (parser.search) {
        const sameQuery = parser.searchParams.get(query);
        if (sameQuery) {
            try {
                const values = JSON.parse(sameQuery);
                if (values instanceof Array) {
                    if (values.indexOf(value) < 0) {
                        values.push(value);
                    } 
                    return `${parser.pathname + parser.search.replace(`${query}=${sameQuery}`, `${query}=[${values.join(',')}]`)}`;
                }
                return parser.pathname + parser.search;
            } catch (e) {
                return parser.pathname + parser.search;
            }
        }
        return `${parser.pathname + parser.search}&${query}=[${value}]`;
    }
    return `${parser.pathname}?${query}=[${value}]`;
}

export function removeArrayQuery (url:string, query:string, value:string|number) {
    const parser = new URL(url);
    if (parser.search) {
        const values = parseArrayQuery(url, query);
        if (!values) {
            return parser.pathname + parser.search;
        }
        const idx = values.indexOf(value);
        if (idx < 0) {
            return parser.pathname + parser.search;
        }
        values.splice(idx, 1);
        if (values.length === 0) {
            return removeQuery(url, query);
        }
        return parser.pathname + parser.search.replace(`${query}=${parser.searchParams.get(query)}`, `${query}=[${values.join(',')}]`)
    }
    return `${parser.pathname}`;
}