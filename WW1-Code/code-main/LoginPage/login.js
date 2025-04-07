// 切换密码可见性
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bx-hide');
        toggleIcon.classList.add('bx-show');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bx-show');
        toggleIcon.classList.add('bx-hide');
    }
}

// 添加事件监听器检查表单输入
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const username = document.querySelector('input[name="username"]');
    const password = document.querySelector('input[name="password"]');

    form.addEventListener('submit', function(e) {
        if (!username.value || !password.value) {
            e.preventDefault();
            alert('请填写所有必填字段');
        }
    });
});
