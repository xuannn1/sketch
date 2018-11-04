import * as React from 'react';
import './styles/carousel.scss';
import './styles/animate_slide.scss';
import { Card } from './common';

interface Props {
    slides:JSX.Element[];
    indicator?:boolean;
    autoSwitchTime?:number; //ms
}

interface State {
    current:number;
}

export class Carousel extends React.Component<Props, State> {
    public slideCount = this.props.slides.length;
    public slides:(HTMLDivElement|null)[] = new Array(this.slideCount);
    public animIn = 'slideInRight';
    public animOut = 'slideOutLeft';

    public state = {
        current: 0,
    };

    public componentDidMount () {
        const { autoSwitchTime } = this.props;
        if (autoSwitchTime) {
            setInterval(this.updateSlide, autoSwitchTime);
        }
    }

    public updateAnim (i?:number) {
        if (i && i < 0) {
            this.animIn = 'slideInLeft';
            this.animOut = 'slideOutRight';
        } else {
            this.animIn = 'slideInRight';
            this.animOut = 'slideOutLeft'; 
        }
    }

    public updateSlide = (index?:number) => {
        const slideCount = this.props.slides.length;
        this.setState((prevState) => {
            let target = index;

            if (target === undefined) {
                target = prevState.current + 1;
                this.updateAnim();
            } else if (target < 0) {
                target = prevState.current + target;
                this.updateAnim(-1);
            } else {
                this.updateAnim(target - this.state.current);
            }

            return {
                current: (target + slideCount) % slideCount,
            }
        });
    }

    public getNextSlide () {
        return this.slides[(this.state.current + 1) % this.slideCount];
    }

    public getPrevSlide () {
        return this.slides[(this.state.current - 1 + this.slideCount) % this.slideCount];
    }

    public render () {
        return <Card className="carousel">
            <div className="slides">
                { this.props.slides.map((el, i) => 
                    <div key={i}
                        ref={(el) => this.slides[i] = el}
                        className={`slide animated ${this.state.current === i ? this.animIn : this.animOut}`}>
                        { el }
                </div>) }
            </div>


            <a className="prev" onClick={() => this.updateSlide(-1)}>&#10094;</a>
            <a className="next" onClick={() => this.updateSlide()}>&#10095;</a>

            { this.props.indicator &&
                <div className="indicator">
                    { this.props.slides.map((el, i) => 
                        <span key={i}
                            className={`dot ${this.state.current === i && 'active'}`}
                            onClick={() => this.updateSlide(i)}>
                        </span>)
                    }
                </div>
            }
        </Card>;
    }
}