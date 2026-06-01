<?php
include "config/db.php";

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(username,email,password)
            VALUES('$username','$email','$password')";

    if(mysqli_query($conn,$sql)){
        header("Location: login.php");
    }else{
        echo "Hata oluştu";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kayıt Ol</title>

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
    grid-template-columns:1.0fr 1.1fr;
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

.icon-circle{
    width:90px;
    height:90px;
    border-radius:50%;
    background:#fde7f3;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 25px;
    font-size:40px;
}

h2{
    font-size:42px;
    color:#111827;
    margin-bottom:10px;
    text-align:center;
}

.subtitle{
    color:#666;
    margin-bottom:40px;
    font-size:18px;
    text-align:center;
}

label{
    display:block;
    margin-top:20px;
    margin-bottom:10px;
    font-weight:bold;
    color:#111827;
    font-size:18px;
}

input{
    width:100%;
    padding:18px;
    border-radius:16px;
    border:none;
    background:#fdf2f8;
    font-size:16px;
}

input:focus{
    outline:none;
    border:2px solid #ec4899;
}

button{
    width:100%;
    padding:18px;
    margin-top:35px;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,#ec4899,#d946ef);
    color:white;
    font-size:22px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    opacity:0.9;
}

.auth-link{
    text-align:center;
    margin-top:25px;
    font-size:17px;
}

.auth-link a{
    color:#ec4899;
    text-decoration:none;
    font-weight:bold;
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
Aramıza Katılın
</h1>

<p>
Ücretsiz hesap oluşturun ve öğrenci hayatınızı organize etmeye başlayın.
Görevlerinizi, projelerinizi ve zamanınızı daha iyi yönetin.
</p>

<div class="feature-list">

<div class="feature-item">

<span>📊</span>

<div>
<h3>Kolay Kullanım</h3>
<p>Basit ve anlaşılır arayüz</p>
</div>

</div>

<div class="feature-item">

<span>📈</span>

<div>
<h3>Detaylı İstatistikler</h3>
<p>İlerlemenizi takip edin</p>
</div>

</div>

<div class="feature-item">

<span>🔔</span>

<div>
<h3>Akıllı Bildirimler</h3>
<p>Önemli tarihleri kaçırmayın</p>
</div>

</div>

</div>

</div>

</div>

<div class="auth-right">

<div class="box">

<div class="icon-circle">
👤
</div>

<h2>Hesap Oluştur</h2>

<p class="subtitle">
Hemen ücretsiz başlayın
</p>

<form method="POST">

<label>Kullanıcı Adı</label>

<input type="text"
name="username"
placeholder="Adınız Soyadınız"
required>

<label>E-posta</label>

<input type="email"
name="email"
placeholder="ornek@email.com"
required>

<label>Şifre</label>

<input type="password"
name="password"
placeholder="En az 6 karakter"
required>

<button type="submit"
name="register">
Kayıt Ol
</button>

</form>

<div class="auth-link">

Zaten hesabınız var mı?
<a href="login.php">
Giriş Yap
</a>

</div>

</div>

</div>

</div>

</body>
</html>