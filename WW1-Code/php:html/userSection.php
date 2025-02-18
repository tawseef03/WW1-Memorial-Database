<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <title>WW1 Users Section</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
    <nav class = 'navbar'>
        <div class = 'logo'></div>
        <div class='nav-links'>
            <a href='sections.php'>Sections</a>
            <a href='about.php'>About</a>
            
            <?php if($logged_in): ?>
                <a href='userInfo.php' class = 'user'><?php echo
                htmlspecialchars($username); ?><php/a>
                <?php else: ?>
                   <a href='login.php'> Log-in</a>
                   <?php endif; ?>
                </div>
            </nav>

         <div class = 'Main-Page'>
        <div class='welcomeText-container'>
             <h1 class='welcome-Text'>Welcome to the WW1 Site</h1>
                <h2 class = 'subtitle' style = 'display: none;'> WW1 Bradford: Sections Page </h2>
        </div>

        
        <div class='section-container'>
            <div class='section box'>Database 1</div>
            <div class='section box'>Database 2</div>
            <div class='section box'>Database 3</div>
            <div class='section box'>Database 4</div>
            <div class='section box'>Database 5</div>
                </div>

            <script src='script.js'></script>









             
         
 </body>
         </html>
                
