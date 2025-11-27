// resources/js/app.js
import '../css/app.css';

window.Echo.channel('feedback')
    .listen('FeedbackCreated', () => {
        alert("Có phản hồi mới từ người dùng!");
    });
