import * as React from 'react';
import { Card } from './common';

interface Props {
    slides:JSX.Element[];
}

interface State {
}

export class Slider extends React.Component<Props, State> {
    // elements
    public slideContainer:HTMLDivElement = document.createElement('div');
    public slider:HTMLDivElement = document.createElement('div');

    // configs
    public duration = 200;
    public easing = 'ease-out';
    public startIndex = 0;
    public draggable = true;
    public threshold = 20;
    public loop = true;

    // dynamic updates
    public slideCount = this.props.slides.length;
    public containerWidth = 0;
    public current = this.startIndex % this.slideCount;
    public startX = 0;
    public endX = 0;
    public translateOffset = 0;

    public componentDidMount () {
        if (this.slideContainer) {
            this.containerWidth = this.slideContainer.offsetWidth;
        }
    }

    public initSlider () {
        this.slider.style.width = `${this.slideCount}00%`;
    }

    public toggleTransition (enable:boolean) {
        if (enable) {
            this.slider.style.transition = `all ${this.duration}ms ${this.easing}`;
        } else {
            this.slider.style.transform = `all 0ms ${this.easing}`;
        }
    }

    public slideToCurrent (enable?:boolean) {
        const current = this.loop ? this.current + this.slideCount : this.current;
        const offset = - current * (this.containerWidth / this.slideCount);

        if (enable) {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    this.toggleTransition(true);
                    this.slideContainer.style.transform = `translated3d(${offset}px, 0, 0)`;
                }); 
            })
        } else {
            this.slideContainer.style.transform = `translated3d(${offset}px, 0, 0)`;
        }
    }

    public handleDragStart = (x:number) => {
        this.startX = x;
    }

    public handleDrag = (x:number) => {
        this.endX = x;
        this.slider.style.transition = `all -ms ${this.easing}`;
        
        const current = this.loop ? this.current + this.slideCount : this.current;
        const currentOffset = current * (this.containerWidth / this.slideCount);
        const dx = this.endX - this.startX;
        const offset = currentOffset - dx;
        this.slider.style.transform = `translate3d(${-offset}px, 0, 0)`;
    }

    public handleDragEnd = (x:number) => {
        this.toggleTransition(true);
        if (this.endX) {
            const dx = this.endX - this.startX;
            const distance = Math.abs(dx);
            if (dx > 0 && distance > this.threshold) {
                this.prev();
            } else if (dx < 0 && distance > this.threshold){
                this.next();
            }
            const slidePrev = dx > 0 && this.current - 1 < 0;
            const slideNext = dx < 0 && this.current + 1 > 0;
            this.slideToCurrent(slidePrev || slideNext);
        }
        this.startX = 0;
        this.endX = 0;
    }

    public prev () {
        const prevSlide = this.current;
        if (this.loop) {
            if (this.current - 1 < 0) {
                this.toggleTransition(false);
                const tc = this.current + this.slideCount;
                const to = tc + this.slideCount;
                const offset = -to * (this.containerWidth / this.slideCount);
                const dx = this.draggable ? this.endX - this.startX : 0;
                this.slider.style.transform = `translate3d(${offset + dx}px, 0, 0)`;
                this.current = tc - 1;
            } else {
                this.current = this.current - 1;
            }
        } else {
            this.current = Math.max(this.current - 1, 0);
        }

        if (prevSlide !== this.current) {
            this.slideToCurrent(this.loop);
        }
    }

    public next () {
        const prevSlide = this.current;
        if (this.loop) {
            if (this.current + 1 > 0) {
                this.toggleTransition(false);
                const tc = this.current - this.slideCount;
                const to = tc + this.slideCount;
                const offset = -to * (this.containerWidth / this.slideCount);
                const dx = this.draggable ? this.endX - this.startX : 0;
                this.slider.style.transform = `translate3d(${offset + dx}px, 0, 0)`;
                this.current = tc + 1;
            } else {
                this.current += 1;
            }
        } else {
            this.current = Math.min(this.current + 1, 0);
        }

        if (prevSlide !== this.current) {
            this.slideToCurrent(this.loop);
        }
    }

    public render () {
        return <Card style={{
            minHeight: '150px',
        }}>
            <div className="slide-wrap" style={{
                overflow: 'hidden',
                minHeight: 'inherit',
            }}>
                <div className="slide-container"
                    ref={(el) => el && (this.slideContainer = el)}
                    style={{
                        minHeight: 'inherit',
                    }}
                    onTouchStart={(ev) => {
                        ev.stopPropagation();
                        ev.touches[0] && this.handleDragStart(ev.touches[0].pageX);
                    }}
                    onTouchEnd={(ev) => {
                        ev.stopPropagation();
                        ev.touches[0] && this.handleDragEnd(ev.touches[0].pageX);
                    }}
                    onTouchMove={(ev) => {
                        ev.stopPropagation();
                        ev.preventDefault();
                        ev.touches[0] && this.handleDrag(ev.touches[0].pageX);
                    }}
                    onMouseDown={(ev) => {
                        ev.preventDefault();
                        ev.stopPropagation();
                        this.slideContainer.style.cursor = '-webkit-grabbing';
                        this.handleDragStart(ev.pageX);
                    }}
                    onMouseUp={(ev) => {
                        ev.stopPropagation();
                        this.slideContainer.style.cursor = '-webkit-grab';
                        this.handleDragEnd(ev.pageX);
                    }}
                    onMouseMove={(ev) => {
                        ev.preventDefault();
                        this.handleDrag(ev.pageX);
                    }}
                    onMouseLeave={(ev) => {
                        this.slideContainer.style.cursor = '-webkig-grab';
                        this.endX = ev.pageX;
                        this.handleDragEnd(ev.pageX);
                    }}>
                    <div className="slider"
                        style={{
                            width: `${this.slideCount}00%`,
                            minHeight: 'inherit',
                            display: 'flex',
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                        ref={(el) => el && (this.slider = el)}>

                        { this.props.slides.map((slide, i) => 
                            <div key={i} className="slide" style={{
                                flex: 1,
                                textAlign: 'center',
                            }}>{slide}</div>)
                        }

                    </div>
                </div>
            </div>
        </Card>;
    }
}