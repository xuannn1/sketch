import * as React from 'react';
import { ResData } from '../../../config/api';
import { Card } from '../common';
import { Link as a } from 'react-router-dom';
import { addArrayQuery, removeArrayQuery } from '../../../utils/url';
import { indexEq } from '../../../utils/id';

enum TagListType {
    unSelecteTags,
    initTags,
    fullTags,
}

interface Props {
    tags:ResData.Tag[];
    selectedTags?:number[];
    getFullList:() => void;
}
interface State {
    tagListType:TagListType;
}

export class Tags extends React.Component<Props, State> {
    public selectedTags:ResData.Tag[] = [];
    public state = {
        tagListType: this.props.selectedTags ? TagListType.initTags : TagListType.unSelecteTags,
    };

    constructor (props) {
        super(props);

        const { selectedTags, tags } = props;
        if (selectedTags) {
            for (let i = 0; i < selectedTags.length; i ++) {
                const tagId = selectedTags[i];
                const idx = indexEq(tags, tagId);
                if (idx >= 0) {
                    this.selectedTags.push(tags[idx]);
                }
            }
        }
    }

    public render () {
        const renderType:{[tagListType:number]:() => JSX.Element} = {
            [TagListType.unSelecteTags]: this.renderInitTags,
            [TagListType.fullTags]: this.renderFullTags,
            [TagListType.initTags]: this.renderSelectedTags,
        }
        
        return <Card className="book-tags">
            {renderType[this.state.tagListType]()}
        </Card>;
    }

    public renderSelectedTags = () => {
        console.log(this.selectedTags);
        const tags = this.selectedTags;
        return <div className="short_list">
            <div className="buttons">
                <span>筛选标签:</span>
                {tags.map((tag, idx) => <Tag
                    id={tag.id}
                    text={tag.attributes.tag_name}
                    isSelected={true}
                    key={idx}
                />)}
                <a className="tag" onClick={() => {
                    this.setState({tagListType: TagListType.fullTags})
                    this.props.getFullList();
                }}>更多</a>
            </div>
        </div>
    }

    public renderInitTags = () => {
        const { tags } = this.props;
        return <div className="short_list">
            <div className="field has-addons" style={{ width: '100%' }}>
                <p className="control" style={{ flex: 1 }}>
                    <Tag id={1} text="原创" type="channels" className="button is-fullwidth" />
                </p>
                <p className="control" style={{ flex: 1 }}>
                    <Tag id={2} text="同人" type="channels" className="button is-fullwidth" />
                </p>
            </div>
            <div className="tags">
                {tags.map((tag) => <Tag id={tag.id} text={tag.attributes.tag_name} key={tag.id} />)}
                <a className="tag" onClick={() => {
                    this.setState({tagListType: TagListType.fullTags})
                    this.props.getFullList();
                }}>更多</a>
            </div>
        </div>;
    }

    public renderFullTags = () => {
        const { tags } = this.props;
        const tagTypes:{[type:string]:ResData.Tag[]} = {};

        for (let i = 0; i < tags.length; i ++) {
            const tag = tags[i];
            const type = tag.attributes.tag_type;
            if (type === '大类') { continue; }
            if (tagTypes[type]) {
                tagTypes[type].push(tag);
            } else {
                tagTypes[type] = [tag];
            }
        }

        return <div className="full_list">
            {Object.keys(tagTypes).map((type, idx) =>
                <div className="li" key={idx}>
                    <div className="tags">
                        <span>{type}</span>
                        {tagTypes[type].map((tag) => {
                            const idx = this.selectedTags.indexOf(tag);
                            return <Tag
                                onClick={() => {
                                    if (idx < 0) {
                                        this.selectedTags.push(tag);
                                    } else {
                                        this.selectedTags.splice(idx, 1);
                                    }
                                }}
                                id={tag.id}
                                text={tag.attributes.tag_name}
                                isSelected={idx >= 0}
                                key={tag.id} />; 
                        })}
                    </div>
                </div>     
            )}
            <div className="li">
                <a className="button is-fullwidth" onClick={() => {}}>点击加载同人标签</a>
            </div>
            <div className="li">
                <a className="button is-fullwidth" onClick={() => {
                    this.setState({tagListType: TagListType.initTags})
                    // fixme: send tags to server and get these tags
                }}>筛选</a>
            </div>
        </div>;
    }
}

class Tag extends React.Component<{
    id:number;
    text:string;
    type?:'channels'|'tags';
    className?:string;
    onClick?:() => void;
    isSelected?:boolean;
}, {
    selected:boolean;
}> {
    public state = {
        selected: this.props.isSelected || false,
    };

    public render () {
        const type = this.props.type || 'tags';
        return <a
            className={(this.props.className || 'tag') + (this.state.selected && ' is-primary' || '')}
            onClick={() => {
                this.setState((prevState) => ({ selected: !prevState.selected }));
                this.props.onClick && this.props.onClick();
            }}
        >{this.props.text}</a>;
    }
}