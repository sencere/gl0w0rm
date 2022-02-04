import React from "react"; 

class Create extends React.Component {
    constructor(props) {
        super(props);
        this.state = {value: ''};
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {    this.setState({value: event.target.value});  }
    handleSubmit(event) {
        alert('A name was submitted: ' + this.state.value);
        event.preventDefault();
    }

    render() {
        const crsfToken = document.querySelector('meta[name="csrf-token"]').content;
        return (
            <div>
                <h1>Publish a Post</h1>
                <hr />
                <form method="post" action="/posts">
                    <input type="hidden" name="_token" value={crsfToken} />
                    react
                </form>
            </div>
        );
    }
}

export default Create;
