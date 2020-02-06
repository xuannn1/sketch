export type textFormat = 'plaintext' | 'markdown' | 'bbcode';

// import BBCODE from 'bbcode-html';
// import bbobHTML from '@bbob/html';
// import presetHTML5 from '@bbob/preset-html5';
import * as _bbcodeUtil from './bbcode';
// import { debug } from 'util';
debugger;
const converter = new _bbcodeUtil.HTML2BBCode({
    imagescale: true,
    transsize: true,
    nolist: false,
    table: true,
    noalign: false,
    noheadings: false,
  });

export function bbcode2html(bbcode){
    return _bbcodeUtil.bbcode2html(bbcode);
    // return bbobHTML(bbcode, presetHTML5());
    // return BBCODE.bbcode.render(bbcode);
}
export function html2bbcode(html) {
    const result = converter.feed(html).toString();
    debugger;
    return result;
}

export function test(bbcode) {
    const html = bbcode2html(bbcode);
    const _bbcode = html2bbcode(html);
    debugger;
    if (bbcode != _bbcode) {
        console.log(bbcode == _bbcode);
        console.error(_bbcode, bbcode, html);
    }
}
