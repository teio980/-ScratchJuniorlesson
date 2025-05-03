// 获取所有的功能按钮
const navbarButtons = document.querySelectorAll('.navbar button');
const dynamicContent = document.getElementById('dynamic-content');

// 页面加载函数，传入要加载的页面 URL 和过渡效果
function loadPage(url, transitionType) {
    // 清空当前内容并应用退出动画
    dynamicContent.classList.remove('active');
    dynamicContent.classList.add('fade-out');

    // 使用 Fetch API 获取新的页面内容
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // 更新动态内容区
            dynamicContent.innerHTML = data;

            // 移除过渡效果，添加新的动画效果
            dynamicContent.classList.remove('fade-out');
            dynamicContent.classList.add('active', transitionType);
        });
}

// 为每个导航按钮绑定点击事件
navbarButtons.forEach(button => {
    button.addEventListener('click', function() {
        const targetPage = this.getAttribute('data-target'); // 获取按钮对应的页面 URL
        const transitionType = this.getAttribute('data-transition'); // 获取按钮设置的过渡类型
        loadPage(targetPage, transitionType);
    });
});
