<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}

include "../config/db.php";

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'");

$user = mysqli_fetch_assoc($query);

$message = "";

if(isset($_POST['update_profile'])){

$username = $_POST['username'];
$email = $_POST['email'];


$photo = $user['profile_photo'];

if(isset($_FILES['profile_photo']) 
&& $_FILES['profile_photo']['name'] != ""){

$fileName = time()."_".$_FILES['profile_photo']['name'];

$tmpName = $_FILES['profile_photo']['tmp_name'];

move_uploaded_file(
$tmpName,
"../uploads/".$fileName
);

$photo = $fileName;

}

mysqli_query($conn,
"UPDATE users
SET username='$username',
email='$email',
profile_photo='$photo'
WHERE id='$user_id'");

$_SESSION['username'] = $username;

$message = "Profil başarıyla güncellendi 🚀";

$query = mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'");

$user = mysqli_fetch_assoc($query);

}
if(isset($_POST['change_password'])){

$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

$userQuery = mysqli_query($conn,
"SELECT * FROM users
WHERE id='$user_id'");

$userData = mysqli_fetch_assoc($userQuery);

if(!password_verify($current, $userData['password'])){

$message = "Mevcut şifre yanlış ❌";

}

elseif($new != $confirm){

$message = "Yeni şifreler eşleşmiyor ❌";

}

else{

$newPassword = password_hash($new, PASSWORD_DEFAULT);

mysqli_query($conn,
"UPDATE users
SET password='$newPassword'
WHERE id='$user_id'");

$message = "Şifre başarıyla güncellendi 🔒";

}

}

if(isset($_POST['remove_photo'])){

if($user['profile_photo'] != ""){

$filePath =
"../uploads/".$user['profile_photo'];

if(file_exists($filePath)){
unlink($filePath);
}

mysqli_query($conn,
"UPDATE users
SET profile_photo=''
WHERE id='$user_id'");

$user['profile_photo'] = "";

$message = "Profil fotoğrafı kaldırıldı 🗑";

}

}

$totalTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'")
);

$completedTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
AND status='Tamamlandı'")
);

$totalProjects = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'")
);

$successRate = 0;

if($totalTasks > 0){
$successRate = round(
($completedTasks / $totalTasks) * 100
);
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profilim</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[6].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<?php if($message != ""){ ?>

<div class="notification success"
style="margin-bottom:20px;">

<?php echo $message; ?>

</div>

<?php } ?>



<div class="profile-box">

<div class="profile-image"
onclick="togglePhotoMenu()">

<?php if($user['profile_photo'] != ""){ ?>

<img src="../uploads/<?php echo $user['profile_photo']; ?>">

<?php } else { ?>

<?php echo strtoupper(substr($user['username'],0,1)); ?>

<?php } ?>

</div>

<div id="photoMenu" class="photo-menu">

<form method="POST">

<button type="submit"
name="remove_photo">

🗑 Sil

</button>

</form>

</div>

<div>

<h2>
<?php echo $user['username']; ?>
</h2>

<p style="margin-top:10px; color:#777;">
<?php echo $user['email']; ?>
</p>

<p style="margin-top:10px; color:#d45c9d;">
Öğrenci Proje ve Görev Takip Sistemi Kullanıcısı
</p>

<p style="
margin-top:15px;
background:#f3e8ff;
color:#7c3aed;
display:inline-block;
padding:8px 16px;
border-radius:30px;
font-size:14px;
font-weight:600;
">

🏆 Aktif Kullanıcı

</p>

</div>

</div>

</div>
<div class="box" style="margin-top:25px;">

<h2>Hakkımda 👤</h2>


<p style="
margin-top:20px;
line-height:1.8;
color:#666;
">

Yazılım geliştirme, görev yönetimi ve proje takibi ile ilgileniyorum.
Bu sistemi kendi görevlerimi düzenlemek ve projelerimi yönetmek için kullanıyorum 🚀

</p>

</div>

<div class="box" style="margin-top:25px;">

<h2>Kullanıcı Seviyesi 🏆</h2>

<?php

$level = "Başlangıç";

if($completedTasks >= 5){
$level = "Aktif Kullanıcı";
}

if($completedTasks >= 15){
$level = "Profesyonel";
}

if($completedTasks >= 30){
$level = "Uzman";
}

?>

<div class="task modern-task" style="margin-top:20px;">

<h3>
<?php echo $level; ?>
</h3>

<p style="margin-top:10px;">
Tamamlanan görev sayısına göre otomatik belirlenir 🚀
</p>

</div>

</div>

<div class="settings-grid">
<div class="box">

<h2>Hesap Ayarları</h2>

<div style="margin-top:20px;">

<form method="POST"
enctype="multipart/form-data">

<input type="text"
name="username"
value="<?php echo $user['username']; ?>">

<input type="email"
name="email"
value="<?php echo $user['email']; ?>">

<label class="file-input">

📷 Profil Seç

<input type="file"
name="profile_photo"
hidden>

</label>

<button type="submit"
name="update_profile"
class="complete-btn">

Profili Güncelle

</button>

</form>

</div>


</div>
<div class="box" style="margin-top:25px;">

<h2>Güvenlik 🔒</h2>

<div style="margin-top:20px;">

<form method="POST">

<input type="password"
name="current_password"
placeholder="Mevcut Şifre">

<input type="password"
name="new_password"
placeholder="Yeni Şifre">

<input type="password"
name="confirm_password"
placeholder="Yeni Şifre Tekrar">

<button type="submit"
name="change_password"
class="delete-btn">

Şifreyi Güncelle

</button>

</form>

</div>

</div>
</div>



<?php include "../includes/footer.php"; ?>
</div>

<script src="../assets/js/app.js"></script>
<script>

function togglePhotoMenu(){

const menu =
document.getElementById("photoMenu");

if(menu.style.display == "block"){
menu.style.display = "none";
}

else{
menu.style.display = "block";
}

}

</script> 
</body>
</html>