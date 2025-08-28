export class Product 
{
    productDOM;
    deleteButtonProduct;
    cacheMeasuresByProduct = {};

    constructor(productDOM) {
        this.productDOM = productDOM;
        this.init();
    }

    init() {
        this.deleteButtonProduct = this.productDOM.querySelector('#remove-product');
        this.deleteButtonProduct.addEventListener('click', () => this.removeProduct());
        this.productDOM.querySelectorAll('select[name="product_id"]').forEach(select => {
            select.addEventListener('change', (event) => {
                this.changeProduct(event);
            });
        });
    }

    removeProduct() {
        const container = this.productDOM.parentNode;
        if (container.querySelectorAll('#product').length > 1) {
            this.productDOM.remove();
        } else {
            alert('Необходимо хотя бы одно поле для поиска!');
        }
    }

    async changeProduct(select) {
        const productId = select.target.value;
        const container = select.target.closest('#product');
        const measureSelect = container.querySelector('select[name="measure_id"]');
        if (this.cacheMeasuresByProduct[productId]) {
            this.updateOptionMeasureSelect(measureSelect, this.cacheMeasuresByProduct[productId]);
            return;
        }
        let url = measureSelect.dataset.route;
        url = url.replace('int', productId);
        const response = await fetch(url);
        const data = await response.json();
        const measure = data.data;
        this.cacheMeasuresByProduct[productId] = measure;
        this.updateOptionMeasureSelect(measureSelect, measure);
    }

    updateOptionMeasureSelect(select, measures) {
        select.innerHTML = '<option value="" disabled selected>Выберите меру</option>';
        for (const [id, name] of Object.entries(measures)) {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            select.appendChild(option);
        }
    }
}