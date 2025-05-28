const addProductButton = document.querySelector('#addProductButton');

addProductButton.addEventListener('click', () => {
    const container = document.querySelector('#productContainer');
    const template = document.querySelector('#productTemplate')

    const item = template.content.cloneNode(true);
    container.append(item);
});