import * as React from 'react';
import 'react-quill/dist/quill.snow.css';
import ReactQuill from 'react-quill';
import { bbcode2html, html2bbcode } from '../../../utils/text-formater';
import './text-editor.scss';

// TODO: 表情包
// TODO: 字号要调大一点
// FIXME: 老站的code是inline element，新的code是block element。见test case #complex3 老站允许在code中字体加粗，调字号，甚至创建list。
// TODO: 加链接的UX有点别扭，在没有选中东西的时候提醒用户选中东西。
// TODO: santize html
// TODO: 和谐词过滤
// TODO: 圈人
// TODO: 搞一个style2给私聊页面
// TODO: 图片允许调大小

// OTHER NOTES
// FIXME: if run a test include <code>, then run a test without, the code style still apply (e.g.in default test suit, try test "code", then the next test case). It seems that there is something wrong in the state sync in quill package, fixing may need some time. However, since that we do not really need to update prop.content during runtime, the bug has low priority.

// TODO: there are some lifecycle warnings with this component (e.g.omponentWillUpdate has been renamed), this warning is from the library Quill
// https://github.com/quilljs/quill/issues/2771
// Thre ReactQuill is a react wrapper for Quill, it also has this warning: https://github.com/zenoamaro/react-quill/pull/531
// however, the ReactQuill team is already working on fixing this, and the PR has already fixed the issue https://github.com/zenoamaro/react-quill/pull/549
// As it seems that the maintainer plans to merge this PR soon, I would prefer to wait for a while first. If not, I will clone the reactQuill module and try fix it myself Q.Q

export type textFormat = 'plaintext' | 'markdown' | 'bbcode';

const formats = [
  'size',
  'color', 'background',
  'bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block',
  'list', 'bullet', 'indent',
  'link', 'image',
  'clean',
];

export class TextEditor extends React.Component<{
  content?:string;
  isMarkdown?:boolean;
}, {
  text:string;
}> {
  constructor(props) {
    super(props);
    const text = this.setContent();
    this.state = { text }; // You can also pass a Quill Delta here
    this.handleChange = this.handleChange.bind(this);
  }

  private reactQuillRef:any = React.createRef<ReactQuill>();
  private quillRef:any = null;

  public componentDidMount() {
    this.attachQuillRefs();
  }

  public componentDidUpdate(prevProps, prevState) {
    if (prevProps.content != this.props.content) {
      const text = this.setContent();
      this.setState({text}); // You can also pass a Quill Delta here
    }
    this.attachQuillRefs();
  }

  private imageHandler = () => {
    if (!this.quillRef) {
      return;
    }
    const range = this.quillRef.getSelection();
    // TODO: use a common prompt element
    const value = prompt('输入图片URL');
    if (value) {
        this.quillRef.insertEmbed(range.index, 'image', value, 'user');
      }
    }

  private modules = {
    toolbar: {
      container: [
      [{ 'size': ['small', false, 'large', 'huge'] }],
      [{ 'color': [] }, { 'background': [] }],
      ['bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block'],
      [{'list': 'ordered'}, {'list': 'bullet'}],
      ['link', 'image'],
      ['clean'],
      ],
      handlers: {
        image: this.imageHandler,
      },
    },
      history: {
        delay: 2000,
        maxStack: 200,
        userOnly: true,
      },
  };

  private attachQuillRefs = () => {
    if (typeof this.reactQuillRef.current.getEditor !== 'function') {
      return;
    }
    this.quillRef = this.reactQuillRef.current.getEditor();
  }
  // return text in bbcode format
  public getContent () {
    console.log('[get content]', this.state.text);
    const result = html2bbcode(this.state.text);
    return result;
  }

  private setContent () : string {
    const {content, isMarkdown} = this.props;
    if (content) {
      if (!isMarkdown) {
        const html = bbcode2html(content);
        return html;
      }
    }
    return '';
  }

  private handleChange(value) {
    this.setState({ text: value });
  }

  public render() {
    return (
      <ReactQuill value={ this.state.text }
                  modules={ this.modules }
                  formats={ formats }
                  onChange={ this.handleChange }
                  ref={ this.reactQuillRef }/>
    );
  }
}
