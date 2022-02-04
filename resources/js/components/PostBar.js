import React from 'react';
import axios from 'axios';

class PostBar extends React.Component {

    constructor() {
        super();
        this.state = {
            status: 'hidden',
            subscribedText: 'Subscribed',
            slug: '',
            'channelName': '',
            imageFileName: '',
            userId : ''
        };
    }

    subscribe = () => {
        axios.post('/subscription/' + this.state.slug)
            .then(() => console.log('Subscribed'));
        this.setState({ status : 'subscribed' });
    };

    unsubscribe = () => {
        axios.delete('/subscription/' + this.state.slug)
            .then(() => console.log('UnSubscribed'));

        this.setState({ status : 'unsubscribed' });
    };


    focus = () => {
        this.setState({ subscribedText : 'Unsubscribe' });
    };

    unfocus = () => {
        this.setState({ subscribedText : 'Subscribed' });
    }

    async componentDidMount() {
        const canvas = document.getElementById('landgrass');
        const postId = landgrass.dataset.id;
        let responseData = axios.post('/subscription/status/' + postId, {})
            .then(response => this.setState({
                status: response.data.status,
                slug: response.data.slug,
                channelName: response.data.channelName,
                imageFileName: response.data.imageFileName,
                userId: response.data.userId
            }));
    };

    render() {
        const imageUrl = '/medium/' + this.state.imageFileName;

        if (this.state.status === 'hidden') {
            return (<div className='d-flex'>
                    <div className='p-2'>
                        <a href={"/user/" + this.state.userId}>
                            <img src={imageUrl} />
                        </a>
                    </div>
                    <div className='user-link'>
                        <a href={"/user/" + this.state.userId}>
                            {this.state.channelName}
                        </a>
                    </div>
                    <div className='p-2 ml-auto'>
                    </div>
                </div>
);
        } else if (this.state.status === 'subscribed') {
            return (
                <div className='d-flex'>
                    <div className='p-2'>
                        <a href={"/user/" + this.state.userId}>
                            <img src={imageUrl} />
                        </a>
                    </div>
                    <div className='user-link'>
                        <a href={"/user/" + this.state.userId}>
                            {this.state.channelName}
                        </a>
                    </div>
                    <div className='p-2 ml-auto'>
                        <button className="btn btn-danger" onMouseEnter={this.focus} onMouseLeave={this.unfocus} onClick={this.unsubscribe}>
                        {this.state.subscribedText}
                        </button>
                    </div>
                </div>
            );
        } else if (this.state.status === 'unsubscribed') {
            return (
                <div className='d-flex'>
                    <div className='p-2'>
                        <a href={"/user/" + this.state.userId}>
                            <img src={imageUrl} />
                            <img src={imageUrl} />
                        </a>
                    </div>
                    <div className='user-link'>
                        <a href={"/user/" + this.state.userId}>
                            {this.state.channelName}
                        </a>
                    </div>
                    <div className='p-2 ml-auto'>
                        <button className="btn btn-purple" onClick={this.subscribe}>
                            Subscribe
                        </button>
                    </div>
                </div>
            );
        }
    }
}

export default PostBar;
