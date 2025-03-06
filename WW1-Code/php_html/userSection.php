<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set character encoding to UTF-8 -->
    <meta charset="UTF-8">
    <!-- Page title -->
    <title>WW1 Users Section</title>
    <!-- Link to external stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- Link to external JavaScript file -->
    <script src="script.js"></script>
</head>

<body>
    <!-- Navigation bar -->
    <nav class='navbar'>
        <!-- Website logo container -->
        <div class='logo'></div>
        <!-- Navigation links -->
        <div class='nav-links'>
            <a href='sections.php'>Sections</a> <!-- Link to Sections page -->
            <a href='about.php'>About</a> <!-- Link to About page -->

            <!-- Display different content based on user login status -->
            <?php if ($logged_in): ?>
                <!-- If user is logged in, display username and link to user info page -->
                <a href='userInfo.php' class='user'><?php echo htmlspecialchars($username); ?></a>
            <?php else: ?>
                <!-- If user is not logged in, display login link -->
                <a href='login.php'>Log-in</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main page content -->
    <div class='Main-Page'>
        <!-- Welcome text container -->
        <div class='welcomeText-container'>
            <h1 class='welcome-Text'>Welcome to the WW1 Site</h1> <!-- Main title -->
            <h2 class='subtitle' style='display: none;'>WW1 Bradford: Sections Page</h2> <!-- Subtitle, hidden by default -->
        </div>

        <!-- Sections container -->
        <div class='section-container'>
            <div class='section box'>Database 1</div> <!-- Section 1 -->
            <div class='section box'>Database 2</div> <!-- Section 2 -->
            <div class='section box'>Database 3</div> <!-- Section 3 -->
            <div class='section box'>Database 4</div> <!-- Section 4 -->
            <div class='section box'>Database 5</div> <!-- Section 5 -->
        </div>
    </div>

    <!-- Link to external JavaScript file -->
    <script src='script.js'></script>
</body>
</html>