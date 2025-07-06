import './bootstrap';

window.Echo.channel('message-event').listen('MessageEvent', (e) => {
    const $container = $('#messages');
    const $newMessage = `
<div>
    <div class="fw-bold">
        ${e.username}, ${e.datetime}
    </div>
    <div class="mb-4">${e.message}</div>
</div>
    `;
    $container.prepend($newMessage);
});
