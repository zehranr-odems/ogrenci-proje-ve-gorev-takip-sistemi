<?php
session_start();

include "../config/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bildirimler</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[5].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Bildirimler 🔔</h2>

<?php

$notifications = [];

/* PROJE SAYISI */

$totalProjects = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'")
);

/* SON PROJELER */

$recentProjects = mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'
ORDER BY id DESC
LIMIT 2");

/* MOTİVASYON */

if($totalProjects == 0){

$notifications[] = [
"type" => "info",
"text" => "🚀 İlk projeni yükleyerek başlamaya ne dersin?",
];

}

elseif($totalProjects >= 1){

$notifications[] = [
"type" => "success",
"text" => "🔥 Şu ana kadar ".$totalProjects." proje ekledin. Harika gidiyorsun!",
];

}

if($totalProjects >= 5){

$notifications[] = [
"type" => "today",
"text" => "🏆 5+ proje tamamlandı. Portföyün güçleniyor!",
];

}

/* SON EKLENEN PROJELER */

while($project = mysqli_fetch_assoc($recentProjects)){

$notifications[] = [
"type" => "info",
"text" => "📁 '".$project['title']."' projesi sisteme eklendi.",
"time" => strtotime($project['created_at'])
];

}

/* GECİKEN GÖREVLER */

$lateTasks = mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
AND due_date < CURDATE()
AND status!='Tamamlandı'");

while($task = mysqli_fetch_assoc($lateTasks)){

$notifications[] = [
"type" => "late",
"text" => "⏰ '".$task['title']."' görevi gecikti.",
"time" => strtotime($task['created_at'])
];

}

/* TAMAMLANANLAR */

$completed = mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
AND status='Tamamlandı'
ORDER BY id DESC
LIMIT 3");

while($task = mysqli_fetch_assoc($completed)){

$notifications[] = [
"type" => "success",
"text" => "✅ '".$task['title']."' tamamlandı.",
"time" => strtotime($task['created_at'])
];

}

/* BUGÜN TAMAMLANAN SAYISI */

$todayCompleted = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
AND status='Tamamlandı'
AND DATE(updated_at)=CURDATE()")
);

if($todayCompleted >= 3){

$notifications[] = [
"type" => "success",
"text" => "🎉 Bugün ".$todayCompleted." görev tamamladın!",
"time" => strtotime($task['created_at'])
];

}

$today = date("Y-m-d");

$todayTasks = mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
AND due_date='$today'");

while($task = mysqli_fetch_assoc($todayTasks)){

$notifications[] = [
"type" => "today",
"text" => "📅 '".$task['title']."' bugün teslim edilmeli.",
"time" => strtotime($task['created_at'])
];

}

?>

<?php

function timeAgo($time){

$diff = time() - $time;

if($diff < 60){
    return "Az önce";
}

elseif($diff < 3600){
    return floor($diff / 60)." dk önce";
}

elseif($diff < 86400){
    return floor($diff / 3600)." saat önce";
}

else{
    return floor($diff / 86400)." gün önce";
}

}

?>

<?php if(count($notifications) > 0){ ?>

<?php foreach($notifications as $n){ ?>

<div class="notification <?php echo $n['type']; ?>">

<div>

<div style="
display:flex;
justify-content:space-between;
align-items:center;
gap:20px;
">

<p>
<?php echo $n['text']; ?>
</p>

<span style="
font-size:13px;
opacity:0.8;
white-space:nowrap;
">

<?php if(isset($n['time'])){ ?>

<span style="
font-size:13px;
opacity:0.8;
white-space:nowrap;
">

🕒 <?php echo timeAgo($n['time']); ?>

</span>

<?php } ?>

</span>

</div>

</div>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty-box">

Bildirim bulunmuyor 🔔

</div>

<?php } ?>

<?php include "../includes/footer.php"; ?>

</div>

<script src="../assets/js/app.js"></script>

</body>
</html>