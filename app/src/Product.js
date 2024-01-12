import React, { useState } from "react";

class Product extends React.Component {
    constructor(props) {
        super(props);
        this.product = props.product;
        this.token = props.token;
        this.showAlert = props.showAlert;
        this.state = {
            editing: false,
            formValues: {
                productName: this.product.productName,
                sku: this.product.sku,
                description: this.product.description
            },
        };
    }

    setProductName = (productName) => {
        this.setState({formValues: {productName: productName, sku: this.state.formValues.sku, description: this.state.formValues.description}});
    }

    setSku = (sku) => {
        this.setState({formValues: {productName: this.state.formValues.productName, sku: sku, description: this.state.formValues.description}});
    }

    setDescription = (description) => {
        this.setState({formValues: {productName: this.state.formValues.productName, sku: this.state.formValues.sku, description: description}});
    }

    toggleEdit = () => {
        this.setState({editing: !this.state.editing, formValues: this.state.formValues});
    }

    handleSubmit = (event) => {
        event.preventDefault();
        fetch(process.env.REACT_APP_API_HOST+'/product/'+this.product.id, {
        method: 'POST',
        body: JSON.stringify(this.state.formValues),
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer '+this.token },
        }).then((response) => {
            if(response.status == 200)
                return response.json();
            else throw new Error('Couldn\'t update the product');
        }).then((data) => {
                this.product = data;
                this.forceUpdate();
            })
        .catch((err) => {
            console.log(err);
            this.showAlert(err);
        });
        this.toggleEdit();
    }

    render() {
        let updatedAt = new Date(this.product.updatedAt);
        let body;
        if(!this.state.editing){
            body = <>
                <h5 class="card-title">{this.product.productName}</h5>
                <h6>{this.product.sku}</h6>
                <p class="card-text">{this.product.description}</p>
                <a href="#" class="btn btn-primary" onClick={this.toggleEdit}>Edit</a>
            </>;
        } else {
            body = <>
                <form onSubmit={this.handleSubmit}>
                    <label for="productName" class="form-label">
                        Product Name:
                    </label>
                    <input
                        type="text"
                        value={this.state.formValues.productName}
                        onChange={(e) => this.setProductName(e.target.value)}
                        class="form-control"
                    />

                    <label for="sku" class="form-label">
                        SKU:
                    </label>
                    <input
                        type="text"
                        value={this.state.formValues.sku}
                        onChange={(e) => this.setSku(e.target.value)}
                        class="form-control"
                    />

                    <label for="description" class="form-label">
                        Description:
                    </label>
                    <textarea
                        class="form-control"
                        onChange={(e) => this.setDescription(e.target.value)}
                    >
                        {this.state.formValues.description}
                    </textarea>
                    <input type="submit" value="Submit" class="btn btn-primary" />
                </form>
            </>
        }
        return <div class="card m-5" style={{width: '18rem', display: 'inline-block'}}>
            <div class="card-body">
                {body}
            </div>
            <div class="card-footer">
                <p>Last modified: {updatedAt.toDateString()+' '+updatedAt.toTimeString().substring(0,8)}</p>
            </div>
        </div>
    };
}

export default Product;