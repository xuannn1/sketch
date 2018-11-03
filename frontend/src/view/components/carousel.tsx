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
    public state = {
        current: 0,
    };

    public componentDidMount () {
        const { autoSwitchTime } = this.props;
        if (autoSwitchTime) {
            setInterval(this.updateSlide, autoSwitchTime);
        }
    }

    public updateSlide = (index?:number) => {
        const slideCount = this.props.slides.length;
        this.setState((prevState) => {
            let target = index;

            if (target === undefined) {
                target = prevState.current + 1;
            } else if (target < 0) {
                target = prevState.current + target;
            }

            return {
                current: (target + slideCount) % slideCount,
            }
        });
    }


    public render () {
        const animIn = 'slideInRight';
        const animOut = 'slideOutLeft';

        return <Card className="carousel">
            <div className="slides">
                { this.props.slides.map((el, i) => 
                    <div key={i}
                        style={{ display: 'flex' }}
                        className={`slide animated ${(this.state.current === i) ? animIn : animOut}`}>
                        { el }
                    </div>) }
            </div>

            <a className="prev" onClick={() => this.updateSlide(-1)}>&#10094;</a>
            <a className="next" onClick={() => this.updateSlide()}>&#10095;</a>

            <div className="indicator">
                { this.props.slides.map((el, i) => 
                    <span key={i}
                        className={`dot ${this.state.current === i && 'active'}`}
                        onClick={() => this.updateSlide(i)}>
                    </span>)}
            </div>
        </Card>;
    }
}