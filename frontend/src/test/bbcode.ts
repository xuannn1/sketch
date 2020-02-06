// html to bbcode


const t_b2h_newlines = `multiple lines\nnew line\n\n\n3 blank lines\n`;

// [{ 'size': ['small', false, 'large', 'huge'] }]  // failed, not supported yet

// [{ 'header': [1, 2, false] }] 


const test_bbcode2html = {
    newlines: `multiple lines\nnew line\n\n\n3 blank lines\n`,
    headers: `[h1]h1[/h1]\n[h2]h2[/h2]\nnormal\n`,
}
// bbcode to html

const test_html2bbcode = {
    'headers': `<h1>h1</h1><br/><h2>h2</h2><br/>normal<br/>`,
}