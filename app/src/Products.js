import React from "react";
import Product from './Product';

class Products extends React.Component {
    constructor(props) {
        super(props);
        this.products = [];
        this.token = props.token;
        this.state = {loading: true};
        this.showAlert = props.showAlert;
      }

    componentDidMount() {
        fetch(process.env.REACT_APP_API_HOST+'/product', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer '+this.token },
        }).then((response) => response.json()).then((data) => {
                this.products = data;
                console.log(this.products);
                this.setState({loading: false});
            })
        .catch((err) => {
            console.log(err);
        });
    }

    render() {
        let loading;
        if(this.state.loading){
            loading = <h3>Loading...</h3>;
        }else {
            loading = <></>
        }

        return <div class="container-sm">
            <h2>Products</h2>

            {loading}

            {this.products.map((product) => {
                console.log(product);
                return <Product product={product} token={this.token} showAlert={this.showAlert} />
            }
            )}
        </div>
    }
}

export default Products;