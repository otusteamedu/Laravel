document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('fib-input');
    const button = document.querySelector('.btn.btn-outline-primary');
    const resultDiv = document.getElementById('fib-result');

    if (button) {
        button.addEventListener('click', () => {
            const n = parseInt(input.value);

            if (isNaN(n) || n < 1 || n > 100) {
                resultDiv.style.display = 'block';
                resultDiv.classList.remove('alert-info');
                resultDiv.classList.add('alert-danger');
                resultDiv.textContent = resultDiv.dataset.error;
                return;
            };

            let fib = [0, 1];
            for (let i = 2; i < n; i++) {
                fib[i] = fib[i - 1] + fib[i - 2];
            }
            const output = fib.slice(0, n).join(', ');
            resultDiv.style.display = 'block';
            resultDiv.classList.remove('alert-danger');
            resultDiv.classList.add('alert-info');
            resultDiv.textContent =  resultDiv.dataset.success + output;
        });
    }
});