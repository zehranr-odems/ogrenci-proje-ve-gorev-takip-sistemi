<?php
session_start();
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

$lateTasks = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tasks 
WHERE user_id='$user_id'
AND due_date < CURDATE()")
);

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- SIDEBAR -->

<?php include "../includes/sidebar.php"; ?>
<script>
document.querySelectorAll('.menu a')[0].classList.add('active');
</script>

<!-- MAIN -->

<div class="main">

<?php include "../includes/topbar.php"; ?>
<?php

$recentProjects = mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'
ORDER BY id DESC
LIMIT 3");

?>
<div class="welcome-banner">

<div>

<h1>
Merhaba <?php echo $_SESSION['username']; ?> 👋
</h1>

<p>
Bugünkü görevlerini yönet ve projelerini takip et.
</p>

<a href="gorevler.php" class="add-btn">
    Yeni Görev Ekle
</a>

</div>

<div class="banner-icon">
🚀
</div>

</div>

<!-- CARDS -->


<div class="project-grid">

<div class="box" style="margin-top:30px;">

<h2>Son Projeler 🚀</h2>

<?php while($project = mysqli_fetch_assoc($recentProjects)) { ?>

<div class="task modern-task">

<h3>
<?php echo $project['title']; ?>
</h3>

<p style="margin-top:10px;">
<?php echo $project['description']; ?>
</p>

<div class="task-actions">

<a href="../uploads/<?php echo $project['file']; ?>"
target="_blank">

<button class="complete-btn">

📂 Aç

</button>

</a>

<a href="projeler.php?delete=<?php echo $project['id']; ?>">

<button class="delete-btn">

🗑 Sil

</button>

</a>

</div>

</div>

<?php } ?>

</div>
<div class="box" style="margin-top:30px;">

<h2>
Görev İstatistikleri 📊
</h2>

<canvas id="taskChart"
style="margin-top:25px;">
</canvas>

</div>

<div class="box">

<a href="takvim.php"
style="text-decoration:none;">

<div class="task modern-task"
style="
text-align:center;
padding:40px 20px;
cursor:pointer;
">

<h2 style="
font-size:28px;
margin-bottom:15px;
color:white;
">

📅 <?php echo date("d.m.Y"); ?>

</h2>

<p style="
font-size:18px;
color:#f3f3f3;
">

<?php

$days = [
"Sunday"=>"Pazar",
"Monday"=>"Pazartesi",
"Tuesday"=>"Salı",
"Wednesday"=>"Çarşamba",
"Thursday"=>"Perşembe",
"Friday"=>"Cuma",
"Saturday"=>"Cumartesi"
];

echo $days[date("l")];

?>

</p>

<p style="
margin-top:20px;
font-size:14px;
background:white;
color:#111;
display:inline-block;
padding:10px 18px;
border-radius:12px;
font-weight:bold;
">

Takvime Git →

</p>

</div>

</a>

<div class="task modern-task"
style="margin-top:20px;">

<h3>
📊 Hızlı İstatistik
</h3>

<?php

$successRate = 0;

if($totalTasks > 0){
    $successRate = round(
    ($completedTasks / $totalTasks) * 100
    );
}

?>

<div style="
margin-top:20px;
width:100%;
height:20px;
background:#1e293b;
border-radius:20px;
overflow:hidden;
">

<div style="
width:<?php echo $successRate; ?>%;
height:100%;
background:#ec4899;
border-radius:20px;
transition:0.4s;
">
</div>

</div>

<p style="
margin-top:15px;
font-size:15px;
color:#f3f3f3;
">

Performans:
<b>%<?php echo $successRate; ?></b>

</p>

</div>
</div>
</div>
<div>
<?php include "../includes/footer.php"; ?>
</div>
<div>
<script src="../assets/js/app.js"></script>
<script>

const ctx =
document.getElementById('taskChart');

new Chart(ctx, {

type: 'doughnut',

data: {

labels: [
'Tamamlandı',
'Bekliyor',
'Gecikti'
],

datasets: [{

data: [
<?php echo $completedTasks; ?>,
<?php echo $pendingTasks; ?>,
<?php echo $lateTasks; ?>
],

backgroundColor: [
'#10b981',
'#ec4899',
'#ef4444'
],

borderWidth:0

}]

},

options: {

responsive:true,

plugins: {

legend: {

labels: {
color:'#888'
}

}

}

}

});

</script>
</body>
</html>