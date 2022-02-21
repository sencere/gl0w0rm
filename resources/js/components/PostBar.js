import React from 'react';
import axios from 'axios';
import EyeIcon from './icons/EyeIcon';
import ThumbsUp from './icons/ThumbsUp';
import ThumbsDown from './icons/ThumbsDown';
import ThumbsUpFill from './icons/ThumbsUpFill';
import ThumbsDownFill from './icons/ThumbsDownFill';

class PostBar extends React.Component {

    constructor() {
        super();
        this.state = {
            status: 'hidden',
            subscribedText: 'Subscribed',
            slug: '',
            'channelName': '',
            imageFileName: '',
            userId : '',
            views: 0,
            thumbStatus: '',
            postId: '',
            votesAllowed: 0,
            upVotesCount: 0,
            downVotesCount: 0,
            votesAllowed: '',
        };
    }

    subscribe = () => {
        axios.post('/subscription/' + this.state.slug);
        this.setState({ status : 'subscribed' });
    };

    unsubscribe = () => {
        axios.delete('/subscription/' + this.state.slug);
        this.setState({ status : 'unsubscribed',
                        subscribedText: 'Subscribe'});
    };

    thumbsAddUp = () => {
        let upVotesCount = this.state.upVotesCount;
        let downVotesCount = this.state.downVotesCount; 
        let voteStatus = this.state.voteStatus;
        axios.post('/posts/' + this.state.postId + '/votes', {'type':'up'});
        console.log('Add Up');
        upVotesCount++;

        if (voteStatus === 'down') {
            downVotesCount--;
        }

        this.setState({'voteStatus':'up',
                       'upVotesCount': upVotesCount,
                       'downVotesCount': downVotesCount
        });
    };

    thumbsAddDown = () => {
        let upVotesCount = this.state.upVotesCount;
        let downVotesCount = this.state.downVotesCount; 
        let voteStatus = this.state.voteStatus;
        axios.post('/posts/' + this.state.postId + '/votes', {'type':'up'});
        axios.post('/posts/' + this.state.postId + '/votes', {'type':'down'});
        downVotesCount++;

        if (voteStatus === 'up') {
            upVotesCount--;
        }

        console.log('Add Down');

        this.setState({'voteStatus':'down',
                       'upVotesCount':upVotesCount,
                       'downVotesCount': downVotesCount});
    };

    thumbsRemoveUp = () => {
        let upVotesCount = this.state.upVotesCount;
        axios.delete('/posts/' + this.state.postId + '/votes', {'type':'up'});
        console.log('Remove Up');
        upVotesCount--;

        this.setState({'voteStatus':'', 
                       'upVotesCount':upVotesCount});
    }

    thumbsRemoveDown = () => {
        let downVotesCount = this.state.downVotesCount;
         
        axios.delete('/posts/' + this.state.postId + '/votes', {'type':'down'});
        console.log('Remove Down');
        downVotesCount--;

        this.setState({'voteStatus':'',
                        'downVotesCount': downVotesCount
        });
    }

    async componentDidMount() {
        const canvas = document.getElementById('landgrass');
        const postId = landgrass.dataset.id;
        this.setState({ postId: postId });
        let responseData = axios.post('/subscription/status/' + postId, {})
            .then(response => this.setState({
                status: response.data.status,
                slug: response.data.slug,
                channelName: response.data.channelName,
                imageFileName: response.data.imageFileName,
                userId: response.data.userId,
                views: response.data.views,
                votesAllowed: response.data.votesAllowed,
                upVotesCount: response.data.upVotesCount,
                downVotesCount: response.data.downVotesCount,
                voteStatus: response.data.voteStatus
            }));
    };

    focus = () => {
        this.setState({ subscribedText : 'Unsubscribe' });
    };

    unfocus = () => {
        this.setState({ subscribedText : 'Subscribed' });
    }

    render() {
        const imageUrl = '/medium/' + this.state.imageFileName;
        let slug = this.state.slug;
        let userId = this.state.userId;
        let subscribeButton = "";
        let votesRender = "";
        // let subscribedText = this.state.subscribedText;
       if (this.state.status === 'subscribed') {
            subscribeButton = <><button className="btn btn-danger" onMouseEnter={this.focus} onMouseLeave={this.unfocus} onClick={this.unsubscribe} >{this.state.subscribedText}</button></>;
        } else if (this.state.status === 'unsubscribed') {
            subscribeButton = <><button className="btn btn-purple" onClick={this.subscribe} >{this.state.subscribedText}</button></>;
        }

        if (this.state.votesAllowed) {
            if (this.state.voteStatus === 'up') {
                votesRender = <>
                        <span onClick={this.thumbsRemoveUp}><ThumbsUpFill size="20" /></span>
                        {this.state.upVotesCount}
                        <span onClick={this.thumbsAddDown}><ThumbsDown size="20" /></span>
                        {this.state.downVotesCount}
                    </>
            } else if (this.state.voteStatus === 'down') {
                votesRender = <>
                        <span onClick={this.thumbsAddUp}><ThumbsUp size="20" /></span>
                        {this.state.upVotesCount}
                        <span onClick={this.thumbsRemoveDown}><ThumbsDownFill size="20" /></span>
                        {this.state.downVotesCount}

                    </>
            } else {
// <span onClick={this.thumbsAddUp}><ThumbsUpFill size="20" /></span>
// <span onClick={this.thumbsRemoveUp}><ThumbsUp size="20" /></span>
// 
// <span onClick={this.thumbsAddDown}><ThumbsDownFill size="20" /></span>
// <span onClick={this.thumbsRemoveDown}><ThumbsDown size="20" /></span>

            votesRender = <>
                    <span onClick={this.thumbsAddUp}><ThumbsUp size="20" /></span>
                    {this.state.upVotesCount}
                    <span onClick={this.thumbsAddDown}><ThumbsDown size="20" /></span>
                    {this.state.downVotesCount}
                </>;
            }
        }

        return (<div className='d-flex'>
                    <div className='p-2'>
                        <a href={"/user/" + this.state.userId}>
                            <img src={imageUrl} />
                        </a>
                    </div>
                    <div className='p-2 user-link'>
                        <a href={"/user/" + this.state.userId}>
                            {this.state.channelName}
                        </a>
                    </div>
                    <div className='p-2'>
                        <EyeIcon size="20" /> {this.state.views}
                    </div>
                    <div className='p-2'>
                        {votesRender}
                    </div>
                    <div className='p-2 ml-auto'>
                        {subscribeButton}
                    </div>
                </div>
        );
    }
}

export default PostBar;
