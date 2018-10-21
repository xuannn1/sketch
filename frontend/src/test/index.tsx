import './test.scss';
import { a } from './a';

const testDom = document.createElement('div');
testDom.innerText = 'hello';
testDom.className = 'testDom';
document.body.appendChild(testDom);
a();

const btn = document.createElement('button');
btn.innerText = 'click me';
btn.onclick = (ev) => {
    import(/* webpackChunkName: "pageA" */ './pageA').then((pageA) => {
        console.log('imported pageA');
    })
}
document.body.appendChild(btn);