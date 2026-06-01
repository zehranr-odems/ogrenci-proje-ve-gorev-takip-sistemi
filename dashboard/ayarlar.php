<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ayarlar</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[7].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Ayarlar ⚙️</h2>

<div class="settings-grid">

<div class="setting-card">

<h3>Tema Ayarları</h3>

<p>
Aydınlık ve karanlık mod arasında geçiş yapabilirsiniz.
</p>

<button type="button" id="themeToggleBtn">
🌙 Tema Değiştir
</button>
<script>

document
.getElementById("themeToggleBtn")
.addEventListener("click", function(){

    document.body.classList.toggle("dark-mode");

    if(document.body.classList.contains("dark-mode")){

        localStorage.setItem(
            "darkMode",
            "enabled"
        );

    }else{

        localStorage.setItem(
            "darkMode",
            "disabled"
        );

    }

});

</script>

</div>

<div class="setting-card">

<h3>Bildirimler</h3>

<p>
Görev ve proje bildirimlerini yönetebilirsiniz.
</p>

<label class="switch">

<input type="checkbox"
id="notificationToggle"
checked>

<span class="slider"></span>

</label>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</div>

<script src="../assets/js/app.js"></script>
<script>

const notificationToggle =
document.getElementById("notificationToggle");

if(localStorage.getItem("notifications") === "off"){

    notificationToggle.checked = false;

}

notificationToggle.addEventListener("change", function(){

    if(this.checked){

        localStorage.setItem(
            "notifications",
            "on"
        );

    }else{

        localStorage.setItem(
            "notifications",
            "off"
        );

    }

});

</script>
</body>
</html>