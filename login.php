<?php
session_start();
include "config/db.php";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard/index.php");

        }else{
            $error = "Şifre yanlış";
        }

    }else{
        $error = "Kullanıcı bulunamadı";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Giriş Yap</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f8fafc;
}

.auth-container{
    min-height:100vh;
    display:grid;
    grid-template-columns:0.9fr 1.1fr;
}

.auth-left{
    background:linear-gradient(135deg,#ec4899,#f9a8d4,#c084fc);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:80px;
    color:white;
}

.left-content{
    max-width:520px;
}

.left-content h1{
    font-size:68px;
    line-height:1.1;
    margin-bottom:30px;
    font-weight:800;
}

.left-content p{
    font-size:22px;
    line-height:1.8;
    margin-bottom:50px;
    opacity:0.95;
}

.feature-list{
    display:flex;
    flex-direction:column;
    gap:35px;
}

.feature-item{
    display:flex;
    align-items:flex-start;
    gap:20px;
}

.feature-item span{
    width:50px;
    height:50px;
    border-radius:50%;
    background:rgba(255,255,255,0.2);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    font-weight:bold;
}

.feature-item h3{
    font-size:28px;
    margin-bottom:5px;
}

.feature-item p{
    margin:0;
    font-size:18px;
}

.auth-right{
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f8fafc;
    padding:40px;
}

.box{
    background:white;
    padding:50px;
    width:100%;
    max-width:500px;
    border-radius:30px;
    box-shadow:0 10px 40px rgba(0,0,0,0.08);
}

h2{
    font-size:42px;
    color:#111827;
    margin-bottom:10px;
}

.subtitle{
    color:#666;
    margin-bottom:30px;
    font-size:18px;
}

input{
    width:100%;
    padding:18px;
    margin-top:18px;
    border-radius:16px;
    border:1px solid #ddd;
    font-size:16px;
}

input:focus{
    outline:none;
    border-color:#ec4899;
}

button{
    width:100%;
    padding:18px;
    margin-top:25px;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,#ec4899,#d946ef);
    color:white;
    font-size:18px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    opacity:0.9;
}

.auth-link{
    text-align:center;
    margin-top:20px;
    font-size:16px;
}

.auth-link a{
    color:#ec4899;
    text-decoration:none;
    font-weight:bold;
}

.error{
    color:red;
    margin-bottom:15px;
}

@media(max-width:900px){

    .auth-container{
        grid-template-columns:1fr;
    }

    .auth-left{
        padding:50px 30px;
    }

    .left-content h1{
        font-size:42px;
    }

}

</style>

</head>

<body>

<div class="auth-container">

<div class="auth-left">

<div class="left-content">

<h1>
Öğrenci Proje Takip Sistemi
</h1>

<p>
Görevlerinizi ve projelerinizi kolayca yönetin.
Modern ve kullanıcı dostu arayüz ile her şeyi tek yerden takip edin.
</p>

<div class="feature-list">

<div class="feature-item">

<span>✓</span>

<div>
<h3>Görev Yönetimi</h3>
<p>Tüm görevlerinizi organize edin</p>
</div>

</div>

<div class="feature-item">

<span>✓</span>

<div>
<h3>Proje Takibi</h3>
<p>Projelerinizi detaylı takip edin</p>
</div>

</div>

<div class="feature-item">

<span>✓</span>

<div>
<h3>İstatistikler</h3>
<p>İlerlemenizi görselleştirin</p>
</div>

</div>

</div>

</div>

</div>

<div class="auth-right">

<div class="box">

<h2>Hoş Geldiniz 👋</h2>

<p class="subtitle">
Hesabınıza giriş yapın
</p>

<?php
if(isset($error)){
    echo "<p class='error'>$error</p>";
}
?>

<form method="POST">

<input type="email"
name="email"
placeholder="E-Posta"
required>

<input type="password"
name="password"
placeholder="Şifre"
required>

<button type="submit"
name="login">
Giriş Yap
</button>

</form>

<div class="auth-link">

Hesabınız yok mu?
<a href="register.php">
Kayıt Ol
</a>

</div>

</div>

</div>

</div>

</body>
</html>