import * as React from 'react';
import 'react-quill/dist/quill.snow.css';
// import * as ReactQuill from 'react-quill';
import ReactQuill from 'react-quill';
import { bbcode2html, html2bbcode, test } from '../../../utils/text-formater';
import './text-editor.scss';
// TODO: support [br]
// TODO: 表情包
// TODO: font size
// TODO: there are some lifecycle warnings with this component (e.g.omponentWillUpdate has been renamed), this warning is from the library Quill
// FIXME: code test first, then other test will have code style
// https://github.com/quilljs/quill/issues/2771
// Thre ReactQuill is a react wrapper for Quill, it also has this warning: https://github.com/zenoamaro/react-quill/pull/531
// however, the ReactQuill team is already working on fixing this, and the PR has already fixed the issue https://github.com/zenoamaro/react-quill/pull/549
// As it seems that the maintainer plans to merge this PR soon, I would prefer to wait for a while first. If not, I will clone the reactQuill module and try fix it myself Q.Q

// TODO: the editor supports upload local img, but probably we only want to accept link for web img.

// TODO: TEST
// [{ 'size': ['small', false, 'large', 'huge'] }], // fail -> use header for now
// [{ 'color': [] }, { 'background': [] }],
// ['bold', 'italic', 'underline', 'strike', 'blockquote'],
// [{'list': 'ordered'}, {'list': 'bullet'}, {'indent': '-1'}, {'indent': '+1'}],
// ['link', 'image'],
// ['clean']

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
    const value = prompt('What is the image URL');
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
        // this.reactQuillRef.clip
        // TODO
        // if (this.sanitize) {
          // this.content = this.domSanitizer.sanitize(SecurityContext.HTML, this.content);
      // }
      // const contents = this.quillEditor.clipboard.convert(this.content);
      // this.quillEditor.setContents(contents, 'silent');
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
