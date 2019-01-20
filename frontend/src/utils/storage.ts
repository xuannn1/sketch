export interface Storage {

}


export function allocStorage () : Storage {
    return {

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