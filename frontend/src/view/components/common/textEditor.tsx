import * as React from 'react';
import { classnames } from '../../../utils/classname';
import 'react-quill/dist/quill.snow.css';
// import * as ReactQuill from 'react-quill';
import ReactQuill, { } from 'react-quill';

// import { Editor, EditorState, RichUtils } from 'draft-js';
// import 'draft-js/dist/Draft.css';
// import './text-editor.scss';

const modules = {
  toolbar: [
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'color': [] }, { 'background': [] }],
    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
    [{'list': 'ordered'}, {'list': 'bullet'}, {'indent': '-1'}, {'indent': '+1'}],
    ['link', 'image'],
    ['clean'],
  ],
    history: {
      delay: 2000,
      maxStack: 200,
      userOnly: true,
    }
};

const formats = [
  'header',
  'size',
  'color', 'background',
  'bold', 'italic', 'underline', 'strike', 'blockquote',
  'list', 'bullet', 'indent',
  'link', 'image',
];

export class TextEditor extends React.Component<{
}, {
  text:string;
}> {
  constructor(props) {
    super(props);
    this.state = { text: '' }; // You can also pass a Quill Delta here
    this.handleChange = this.handleChange.bind(this);
  }


  handleChange(value) {
    console.log(value);
    this.setState({ text: value });
  }

  render() {
    return (
      <ReactQuill value={this.state.text}
                  modules={modules}
                  formats={formats}
                  onChange={this.handleChange} />
    );
  }
}
// export class TextEditor extends React.Component<{
// }, {
//   editorState:EditorState,
// }> {

//   private onChange:(s:EditorState) => void;
//   private setEditor:(e:Editor) => void;
//   private editor:Editor;
//   constructor(props) {
//     super(props);
//     this.state = {editorState: EditorState.createEmpty()};
//     this.onChange = (editorState) => this.setState({editorState});
//     this.setEditor = (editor) => {
//       this.editor = editor;
//     };
//   }

//   public render() {
//     return (
//       <div style={styles.editor}>
//         <button onClick={this.onBoldClick}>Bold</button>
//         <Editor
//           ref={this.setEditor}
//           editorState={this.state.editorState}
//           handleKeyCommand={this.handleKeyCommand}
//           onChange={this.onChange}
//         />
//       </div>
//     );
//   }

//   // handle key commnad
//   private handleKeyCommand = (command) => {
//     const {editorState} = this.state;
//     const newState = RichUtils.handleKeyCommand(editorState, command);
//     if (newState) {
//       this.onChange(newState);  // handled
//       return true;
//     }
//     return false; // not handled
//   }

//   private onBoldClick = () => {
//     this.onChange(RichUtils.toggleInlineStyle(this.state.editorState, 'BOLD'));
//   }
// }

// const styles = {
//   editor: {
//     border: '1px solid gray',
//     minHeight: '6em',
//   }
// }
