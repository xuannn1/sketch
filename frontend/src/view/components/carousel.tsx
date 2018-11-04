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
}

export class Carousel extends React.Component<Props, State> {
    public slideCount = this.props.slides.length;
    public slides:(HTMLDivElement|null)[] = new Array(this.slideCount);
    public el:HTMLDivElement|null = null;
    public slideContainerEl:HTMLDivElement|null = null;
    public slideWrapEl:HTMLDivElement|null = null;

    public animIn = 'slideInRight';
    public animOut = 'slideOutLeft';
    public container_width = 0;
    public translateX = 0;
    public easing = 'ease-out';
    public loop = true;
    public currentSlide = 0;
    public threshold = 20;

    public componentDidMount () {
        const { autoSwitchTime } = this.props;
        if (autoSwitchTime) {
            setInterval(this.updateSlide, autoSwitchTime);
        }

        if (this.el) {
            this.container_width = this.el.offsetWidth;
        }
    }

    public updateSlide = (index?:number) => {
        const slideCount = this.props.slides.length;
        let target = index;

        if (target === undefined) {
            target = this.currentSlide + 1;
        } else if (target < 0) {
            target = this.currentSlide + target;
        }

        this.currentSlide = (target + slideCount) % slideCount;
    }

    public endX = 0;
    public startX = 0;

    public handleDragStart = (pageX:number) => {
        if (!this.slideContainerEl || !this.slideWrapEl) { return; }
        this.slideWrapEl.style.cursor = '-webkit-grabbing';
        this.startX = pageX;
    }

    public handleDrag = (pageX:number) => {
        if (!this.slideContainerEl || !this.slideWrapEl) { return; }
        this.slideContainerEl.style.transition = `all 0ms ${this.easing}`;
        this.slideContainerEl.style.webkitTransition = `all 0ms ${this.easing}`;

        this.endX = pageX

        const currentSlide = this.loop ? this.currentSlide + this.slideCount : this.currentSlide;
        const currentOffset = this.currentSlide * (this.container_width / this.slideCount);
        const offset = currentOffset - (this.endX - this.startX);
        this.slideContainerEl.style.transform = `translate3d(${-1 * offset}px, 0, 0)`;
    }

    public handleDragCancel = () => {
        if (!this.slideContainerEl || !this.slideWrapEl) { return; }
        this.slideContainerEl.style.transition = `all 200ms ${this.easing}`;
        this.slideWrapEl.style.cursor = '-webkit-grab';

        if (this.endX) {
            const dx = this.endX - this.startX;
            const distance = Math.abs(dx);
        }
    }

    public render () {
        return <Card className="carousel" ref={(el) => this.el = el}>

            <div className="slide-wrap" 
                ref={(el) => this.slideWrapEl = el}
                onMouseDown={(ev) => this.handleDragStart(ev.pageX)}
                onMouseMove={(ev) => {
                    ev.preventDefault();
                    this.handleDrag(ev.pageX);
                }}
                onMouseUp={(ev) => this.handleDragCancel()}
                onTouchEnd={(ev) => this.handleDragCancel()}
                onTouchMove={(ev) => {
                    if (!ev.touches.length) { return; }
                    ev.preventDefault();
                    this.handleDrag(ev.touches[0].pageX);
                }}
                onTouchStart={(ev) => this.handleDragStart(ev.touches[0] && ev.touches[0].pageX)}>

                <div className="slide-container"
                    ref={(el) => this.slideContainerEl = el}
                    style={{
                        width: `${this.slideCount}00%`,
                        transition: '200ms all ease-out 0s',
                    }}>

                    { this.props.slides.map((el, i) => 
                        <div key={i}
                            ref={(el) => this.slides[i] = el}
                            className={`slide`}>
                            { el }
                    </div>) } 

                </div>
            </div>

            <a className="prev" onClick={() => this.updateSlide(-1)}>&#10094;</a>
            <a className="next" onClick={() => this.updateSlide()}>&#10095;</a>

            { this.props.indicator &&
                <div className="indicator">
                    { this.props.slides.map((el, i) => 
                        <span key={i}
                            className={`dot`}
                            onClick={() => this.updateSlide(i)}>
                        </span>)
                    }
                </div>
            }
        </Card>;
    }
}