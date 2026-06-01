<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}

include "../config/db.php";

$user_id = $_SESSION['user_id'];

$totalTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks WHERE user_id='$user_id'")
);

$completedTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks 
WHERE user_id='$user_id'
AND status='Tamamlandı'")
);

$pendingTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks 
WHERE user_id='$user_id'
AND status='Bekliyor'")
);

$successRate = 0;

$totalProjects = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'")
);

if($successRate >= 80){
    $badge = "🏆 Mükemmel";
}
elseif($successRate >= 50){
    $badge = "🚀 İyi";
}
else{
    $badge = "⚡ Gelişiyor";
}

if($totalTasks > 0){
    $successRate = round(($completedTasks / $totalTasks) * 100);
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>İstatistikler</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[4].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Genel Performans</h2>
<p style="
margin-top:10px;
color:#888;
">

Sistem görev tamamlama oranınızı analiz ediyor.

</p>

<div style="margin-top:25px;">

<div style="
width:100%;
height:25px;
background:#ffe3f0;
border-radius:20px;
overflow:hidden;
">

<div style="
width:<?php echo $successRate; ?>%;
height:100%;
background:#e979b4;
border-radius:20px;
">
</div>

</div>

<p style="margin-top:15px; color:#777;">
Görev tamamlama oranınız:
<b>%<?php echo $successRate; ?></b>
</p>

</div>

</div>

<div class="box">

<h2>Çalışma Özeti</h2>

<div style="
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-top:20px;
">

<div class="task">
<h3>Aktif Görevler</h3>
<p><?php echo $pendingTasks; ?> görev devam ediyor.</p>
</div>

<div class="task">
<h3>Tamamlanan Görevler</h3>
<p><?php echo $completedTasks; ?> görev başarıyla tamamlandı.</p>
</div>
<div style="
margin-top:30px;
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
">

<div class="task modern-task">

<h3>
📁 Toplam Proje
</h3>

<p style="margin-top:15px;">

<?php echo $totalProjects; ?>
  proje sisteme kayıtlı.

</p>

</div>

<div class="task modern-task">

<h3>
<?php echo $badge; ?>
</h3>

<p style="margin-top:15px;">

<?php

if($successRate >= 80){
    echo "Görev yönetimin harika gidiyor 🔥";
}
elseif($successRate >= 50){
    echo "İyi ilerliyorsun 🚀";
}
else{
    echo "Görevlerini tamamlamaya devam et ⚡";
}

?>

</p>

</div>

</div>

</div>

</div>
<?php include "../includes/footer.php"; ?>
</div>

<script src="../assets/js/app.js"></script>

</body>
</html>