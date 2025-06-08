export class Button {

    constructor(element) {
        this.init(element);
    }

    init(element) {
        const nameElement = element.id;
        if (element.querySelector('.create-' + nameElement + '-btn')) {
            element.querySelector('.create-' + nameElement + '-btn').addEventListener('click', (ev) => {
                this.create(ev.currentTarget);
            });
        }
        if (element.querySelector('.store-' + nameElement + '-btn')) {
            element.querySelector('.store-' + nameElement + '-btn').addEventListener('click', (ev) => {
                this.store(ev.currentTarget);
            });
        } 
        if (element.querySelectorAll('.edit-' + nameElement + '-btn')) {
            element.querySelectorAll('.edit-' + nameElement + '-btn').forEach((el) => {
                el.addEventListener('click', (ev) => {
                    this.edit(ev.currentTarget);
                });
            });
        }
        if (element.querySelectorAll('.update-' + nameElement + '-btn')) {
            element.querySelectorAll('.update-' + nameElement + '-btn').forEach((el) => {
                el.addEventListener('click', (ev) => {
                    this.update(ev.currentTarget);
                });
            });
        }
        if (element.querySelectorAll('.delete-' + nameElement + '-btn')) {
            element.querySelectorAll('.delete-' + nameElement + '-btn').forEach((el) => {
                el.addEventListener('click', (ev) => {
                    this.delete(ev.currentTarget);
                });
            });
        }
    }

    create(btn) {
        const url = btn.dataset.route;
        if (url) {
            window.location.href = url;
        } else {
            console.error('Route не найден в data-route');
        }
    }

    store(btn) {
        const url = btn.dataset.route;
        const redirectUrl = btn.dataset.redirectRoute;
        console.log(redirectUrl);
        if (!url) {
            console.error('Route не найден в data-route');
            alert('Системная ошибка');
            return;
        }
        const fieldsStr = btn.dataset.fields;
        if (!fieldsStr) {
            console.error('Поля для сбора не указаны');
            alert('Системная ошибка');
            return;
        }
        const fields = fieldsStr.split(',');
        const data = {};
        fields.forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                data[fieldName] = input.value;
            } else {
                console.warn(`Поле ${fieldName} не найдено`);
                alert('Системная ошибка');
            }
        }); 
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                alert(response.message);
                return;
            }
            console.log('Успех:', response.data);
            alert(response.message);
            window.location.href = redirectUrl;
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении.');
        });
    }

    edit(btn) {
        const url = btn.dataset.route;
        if (url) {
            window.location.href = url;
        } else {
            console.error('Route не найден в data-route');
        }
    }

    update(btn) {
        const url = btn.dataset.route;
        const redirectUrl = btn.dataset.redirectRoute;
        console.log(url);
        if (!url) {
            console.error('Route не найден в data-route');
            alert('Системная ошибка');
            return;
        }
        const fieldsStr = btn.dataset.fields;
        if (!fieldsStr) {
            console.error('Поля для сбора не указаны');
            alert('Системная ошибка');
            return;
        }
        const fields = fieldsStr.split(',');
        const data = {};
        fields.forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                data[fieldName] = input.value;
            } else {
                console.warn(`Поле ${fieldName} не найдено`);
                alert('Системная ошибка');
            }
        }); 
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                console.error('Ошибка:', response.message);
                alert('Произошла ошибка при удалении.');
                return;
            }
            console.log('Успех:', response.data);
            alert(response.message);
            window.location.href = redirectUrl;
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении.');
        });
    }

    delete(btn) {
        const url = btn.dataset.route;
        if (!url) {
            console.error('Route не найден в data-route');
            alert('Системная ошибка');
            return;
        }
        fetch(url, {
            method: 'DELETE',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                console.error('Ошибка:', response.message);
                alert('Произошла ошибка при удалении.');
                return;
            }
            console.log('Успех:', response.data);
            alert(response.message);
            location.reload();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при удалении.');
        });
    }
}
