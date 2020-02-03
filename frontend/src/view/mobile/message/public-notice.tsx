import * as React from 'react';
import { API, ResData, ReqData } from '../../../config/api';
import { MobileRouteProps } from '../router';
// import { StatusNav } from './nav';
import { Page } from '../../components/common/page';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';
import { List } from '../../components/common/list';
import { pageStyle, publicNoticeCardStyle } from './styles';
import { mockReplyNotifications } from './mock-data';
import { Dialogue } from './dialogue';
import { ExpandableMessage } from '../../components/message/expandable-message';

interface State {
  publicNoticeData:API.Get['/publicnotice'];
}

// public notice data can be passed via props (it's optional)
export class PublicNotice extends React.Component<MobileRouteProps, State> {
  public state:State = {
    publicNoticeData:{
      public_notices: [],
    },
  };

  public async componentDidMount() {
    let publicNoticeData;
    if (this.props.location.state && this.props.location.state.publicNoticeData) {
      publicNoticeData = this.props.location.state.publicNoticeData;
    } else {
      publicNoticeData = await this.props.core.db.getPublicNotice()
                                .catch((e) => {
                                  console.log(e);
                                  return this.state.publicNoticeData;
                                });
    }
    console.log(publicNoticeData);
    debugger;
    this.setState({publicNoticeData});
  }

  private renderNotice (notice:ResData.PublicNotice) {
    const title = notice.attributes.title ? notice.attributes.title : '通知';
    const authorName = notice.author ? notice.author.attributes.name : '管理员';
    const time = notice.attributes.created_at;
    const id = notice.id;
    const content = notice.attributes.body;
    const footer = `${authorName} ${time}`;

    return (
      <ExpandableMessage
        key={'pn' + id}
        title={title}
        uid={'pn' + id}
        content={content}
        footer={footer}/>);
  }

  public render () {
    return (<Page style={pageStyle}
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          公共通知
        </NavBar>}>
        <Card style={ publicNoticeCardStyle }>
          {this.state.publicNoticeData.public_notices.map((n) => this.renderNotice(n))}
        </Card>
      </Page>);
  }
}

// <Accordion
//     title={text('title', 'accordion title')}
//     arrow={boolean('arrow', true)}
//   >
//     <List>
//       <List.Item>1</List.Item>
//       <List.Item>2</List.Item>
//     </List>
//   </Accordion>

//   // redirect user to public notice page
//   private readPublicNotice = () => {
//     // TODO: clear all unread notice

//   }
//   /** ===========            user messages           =============== **/
//   private getDialogues() : ResData.Message[] {
//     const { messages } = this.state.messageData;
//     const dialogues:{[key:string]:ResData.Message} = {};
//     const dialoguesArray:ResData.Message[] = [];
//     messages.forEach((m) => {
//       if (!dialogues[m.attributes.poster_id]) {
//         dialogues[m.attributes.poster_id] = m;
//         dialoguesArray.push(m); // so the dialogues are perserved in time order
//       }
//     });
//     return dialoguesArray;
//   }

//   // @param chatWithID - the id of user you are chating with
//   // @param chatWithName - the name of user you are chating with
//   private onClicDialogue = (chatWithID:number, chatWithName:string) => () => {
//     this.props.core.history.push(`/messages/pm/${chatWithID}`, {chatWithName});
//   }

//   private renderMessages () {
//     const dialogues = this.getDialogues();
//     const renderDialogue = (dialogue:ResData.Message) => {
//       const posterName:string = dialogue.poster ? dialogue.poster.attributes.name : '';
//       const posterID:number = dialogue.attributes.poster_id;
//       const seen:boolean = dialogue.attributes.seen;
//       const content:string = dialogue.message_body ? dialogue.message_body.attributes.body : '';

//       return (<List.Item key={dialogue.id} style={{background:'white', marginBottom:'0.3em'}} onClick={this.onClicDialogue(posterID, posterName)}>
//                 <h6 className="is-6" style={seen ? {} : unreadStyle}>{posterName}</h6>
//                 <div style={replyMessageContentStyle}>
//                   <p style={oneLineTruncationStyle}>{!seen ? <React.Fragment><b>[有新消息]</b>{` `}</React.Fragment> : ''}{content}</p>
//                 </div>
//               </List.Item>);
//     };

//     return (<Card style={replyNotificationCardStyle}>
//               <List style={{background:'transparent'}}>
//                 {dialogues.map((d) => renderDialogue(d))}
//               </List>
//             </Card>);
//   }
// }