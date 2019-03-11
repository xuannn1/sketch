import * as React from 'react';
import { Card, NotificationError } from '../../components/common';
import { Carousel } from '../../components/carousel';
import { EventBus } from '../../../utils/events';
import { ResData, APIPost } from '../../../config/api';

interface Props {
    quotes: ResData.Quote[];
    isLoggedIn: boolean;
    windowResizeEvent: EventBus<void>;
    createQuote: (body:string, is_anonymous:boolean, majia: string) => Promise<APIPost['/quote']['res'] | null>;
}

interface State {
    showCreateQuote: boolean
    body: string
    isAnonymous: boolean
    majia: string
    errorMsg: string
}

export class Quotes extends React.Component<Props, State> {
    public state = {
        showCreateQuote: false,
        body: '',
        isAnonymous: false,
        majia: '',
        errorMsg: ''
    }
    public index = 0;
    public render () {
        return <div>
            <Carousel  
                windowResizeEvent={this.props.windowResizeEvent}
                slides={this.props.quotes.map((quote, i) => 
                    <div>
                        <span key={"body"+i}>{quote.attributes.body}</span>
                        <span key={"author"+i}>——{quote.attributes.is_anonymous ? quote.attributes.majia : quote.author.attributes.name}</span>
                    </div>
                )}
                getIndex={(index) => this.index = index}
                indicator={true} />

            {this.props.isLoggedIn &&
            <div>
                <a className="button" onClick={
                    ()=>this.setState({showCreateQuote: this.state.showCreateQuote ? false : true})
                    }>
                    贡献题头
                </a>
                <a className="button is-pulled-right" /*onClick={()=>} TODO: 投掷咸鱼*/>
                    咸鱼 {this.props.quotes[this.index] ? this.props.quotes[this.index].attributes.xianyu : 0}
                </a>

                {this.state.showCreateQuote ? 
                <Card>
                    {this.state.errorMsg && <NotificationError>
                        { this.state.errorMsg }
                    </NotificationError>}
                    新题头：
                    <input className="input" 
                        type="text" 
                        placeholder="不丧不成活~"
                        value={this.state.body}
                        onChange={(ev) => this.setState({body: ev.target.value})}
                        />
                    <a className="button">恢复数据</a>

                    <div className="is-size-7 has-text-grey">
                        （每人每天只能提交一次题头。题头需要审核，题头审核通过的条件是“有品、有趣、有点丧”。不满足这个条件，过于私密，或可能引起他人不适的题头不会被通过。）
                    </div>

                    <label className="checkbox">
                        <input type="checkbox" 
                            checked={this.state.isAnonymous}
                            onChange={(ev) => this.setState({isAnonymous: ev.target.checked})}
                            />
                        马甲？
                    </label>
                    {this.state.isAnonymous &&
                    <input className="input" 
                        type="text" 
                        value={this.state.majia}
                        onChange={(ev) => this.setState({majia: ev.target.value})}
                    />
                    }

                    <a className="button is-full-width" onClick={async (ev) => {
                        if (this.state.body === '') {
                            this.setState({errorMsg: '题头正文 不能为空。'});
                        }
                        else if (this.state.isAnonymous && this.state.majia === '') {
                            this.setState({errorMsg: '马甲不能为空。'})
                        }
                        else {
                            const res = await this.props.createQuote(this.state.body, this.state.isAnonymous, this.state.majia);
                            if (!res) {
                                this.setState({errorMsg: '提交失败。'})
                            }
                            else if (res.code === 422) {
                                this.setState({errorMsg: '题头已存在，请勿重复提交。'})
                            }
                            else {
                                this.setState({showCreateQuote : false, errorMsg: ''})
                            }
                        }
                    }}>
                        提交
                    </a>
                </Card> 
                : <span></span>}
            </div>

            }
        </div>
    }
    public showCreateQuote () {
        return
    }

}