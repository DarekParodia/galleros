<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../php/head.php" ?>
    <link rel="stylesheet" href="./css/tiles.css">
    <script src="./js/login.js"></script>
    <title>Galeros - Login</title>
</head>

<body>
    <?php include "../php/header.php" ?>
    <div class="content-container">
        <section class="gallery-section">
            <h1 class="section-header">Zaloguj się</h1>
            <form action="./login.php" method="post" class="center col form">
                <div class="col">
                    <label for="username">Nazwa Użytkownika</label>
                    <input type="text" name="username" id="username" placeholder="Wprowadź nazwę użytkownika">
                </div>
                <div class="col">
                    <label for="password">Hasło</label>
                    <input type="password" name="password" id="password" placeholder="Wprowadź hasło">
                </div>
                <div class="w-100 checkbox-div">
                    <input type="checkbox" name="password-show" id="password-show">
                    <label for="password-show">Pokaż Hasło</label>
                </div>
                <button type="submit" class="view-button">Zaloguj</button>
            </form>
        </section>
    </div>
</body>

</html>