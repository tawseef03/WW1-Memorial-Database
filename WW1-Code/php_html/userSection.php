<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 设置字符编码为 UTF-8 -->
    <meta charset="UTF-8">
    <!-- 页面标题 -->
    <title>WW1 Users Section</title>
    <!-- 引入外部样式表 -->
    <link rel="stylesheet" href="style.css">
    <!-- 引入外部 JavaScript 文件 -->
    <script src="script.js"></script>
</head>

<body>
    <!-- 导航栏 -->
    <nav class='navbar'>
        <!-- 网站 Logo 容器 -->
        <div class='logo'></div>
        <!-- 导航链接 -->
        <div class='nav-links'>
            <a href='sections.php'>Sections</a> <!-- 跳转到 Sections 页面 -->
            <a href='about.php'>About</a> <!-- 跳转到 About 页面 -->

            <!-- 根据用户登录状态显示不同内容 -->
            <?php if ($logged_in): ?>
                <!-- 如果用户已登录，显示用户名并跳转到用户信息页面 -->
                <a href='userInfo.php' class='user'><?php echo htmlspecialchars($username); ?></a>
            <?php else: ?>
                <!-- 如果用户未登录，显示登录链接 -->
                <a href='login.php'>Log-in</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- 主页面内容 -->
    <div class='Main-Page'>
        <!-- 欢迎文本容器 -->
        <div class='welcomeText-container'>
            <h1 class='welcome-Text'>Welcome to the WW1 Site</h1> <!-- 主标题 -->
            <h2 class='subtitle' style='display: none;'>WW1 Bradford: Sections Page</h2> <!-- 副标题，默认隐藏 -->
        </div>

        <!-- 分区容器 -->
        <div class='section-container'>
            <div class='section box'>Database 1</div> <!-- 分区 1 -->
            <div class='section box'>Database 2</div> <!-- 分区 2 -->
            <div class='section box'>Database 3</div> <!-- 分区 3 -->
            <div class='section box'>Database 4</div> <!-- 分区 4 -->
            <div class='section box'>Database 5</div> <!-- 分区 5 -->
        </div>
    </div>

    <!-- 引入外部 JavaScript 文件 -->
    <script src='script.js'></script>
</body>
</html>