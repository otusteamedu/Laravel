export class Fibonachi {
    
    constructor() {
        this.init();
    }

    init() {
        this.input = document.getElementById('fib-input');
        this.button = document.querySelector('.btn.btn-outline-primary');
        this.resultDiv = document.getElementById('fib-result');
        this.calculate();
    }

    calculate() {
        this.button.addEventListener('click', () => {
            if (!this.input.value) {
                this.input.value = 0;
            }
            let statusCode;
            fetch(this.button.dataset.route.replace('__NUMBER__', this.input.value))
                .then(res => {
                    return res.json();
                })
                .then(res => {
                    statusCode = res.statusCode;
                    if (!res.success) {
                        if (res.statusCode === 400) {
                            this.resultDiv.style.display = 'block';
                            this.resultDiv.classList.remove('alert-info');
                            this.resultDiv.classList.add('alert-danger');
                            this.resultDiv.textContent = this.resultDiv.dataset.error;
                        } else if (res.statusCode === 403) {
                            this.resultDiv.style.display = 'block';
                            this.resultDiv.classList.remove('alert-info');
                            this.resultDiv.classList.add('alert-danger');
                            this.resultDiv.textContent = res.message;
                        } else {
                            console.log(res.message);
                            alert('Системная ошибка!');
                        }
                    } else {
                        const output = res.data.slice(0, this.input.value).join(', ');
                        this.resultDiv.style.display = 'block';
                        this.resultDiv.classList.remove('alert-danger');
                        this.resultDiv.classList.add('alert-info');
                        this.resultDiv.textContent =  this.resultDiv.dataset.success + output;
                    }
                })
                .catch(res => {
                    console.log(res.message);
                    alert('Системная ошибка!');
                })
                .finally(() => {
                    console.log('Статус ответа: ' + statusCode);
                    this.input.value = null;
                })
        });
    }
}