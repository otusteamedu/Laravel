import {Product} from './Product.js';

class Index 
{
    elementDOM;
    addButtonProduct;

    constructor(elementDOM) {
        this.elementDOM = elementDOM;
        this.init();
    }

    init() {
        this.addButtonProduct = this.elementDOM.querySelector('#add-product');
        this.addButtonProduct.addEventListener('click', () => this.addProduct());
        new Product(this.elementDOM.querySelector('#product'));
        this.elementDOM.querySelector('#select-recipe').addEventListener('click', async (e) => {
            e.preventDefault();
            await this.selectRecipe();
        });
    }

    addProduct() {
        const firstProduct = this.elementDOM.querySelector('#product');
        const newProduct = firstProduct.cloneNode(true);
        newProduct.querySelector('select').selectedIndex = 0;
        newProduct.querySelector('input').value = '';
        this.elementDOM.querySelector('#products-container').appendChild(newProduct);
        new Product(newProduct);
    }

    async selectRecipe() {
        const form = this.elementDOM.querySelector('#recipe-form');
        const products = Array.from(form.querySelectorAll('.product-item')).map(block => {
            return {
                product_id: block.querySelector('select[name="product_id"]').value,
                product_value: block.querySelector('input[name="product_value"]').value,
                measure_id: block.querySelector('select[name="measure_id"]').value
            };
        });
        const portions = form.querySelector('input[name="portions"]').value;
        const data = {
            products: products,
            portions: portions
        };
        console.log(data);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('recipes-result').innerHTML = data.data;
            } else {
                alert(data.message || 'Ошибка при подборе рецептов');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    new Index(document.querySelector('.py-12'));
});