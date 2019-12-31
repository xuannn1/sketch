import * as React from 'react';
import { classnames } from '../../../utils/classname';
import './list.scss';

export interface LongListProps {
  /**
   * Does the resource have more entities
   */
  hasMore:boolean;

  /**
   * Should show loading
   */
  isLoading:boolean;

  /**
   * Callback to load more entities
   */
  onLoadMore:() => void;

  /** List props */
  children?:any;
  className?:string;
  arrow?:boolean;
  onClick?:() => void;
  style?:React.CSSProperties;
}

export class LongList extends React.Component<LongListProps, {}> {
  public render() {
    return (
      <div
        className={classnames('list-item', this.props.className)}
        style={this.props.style}
        onClick={this.props.onClick}
      >
        {this.props.arrow && (
          <div className="list-arrow">
            <i className="fas fa-angle-right"></i>
          </div>
        )}
        {this.props.children}
        {this.props.isLoading ?
          <button className="button is-fullwidth is-loading">Loading</button> :
          <button className="button is-fullwidth" onClick={this.props.onLoadMore}>Load More</button>}
      </div>
    );
  }
}
