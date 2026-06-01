<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}

$user_id = $_SESSION['user_id'];


$currentMonth = isset($_GET['month'])
? intval($_GET['month'])
: intval(date("m"));

$currentYear = isset($_GET['year'])
? intval($_GET['year'])
: intval(date("Y"));

if($currentMonth <= 0){
    $currentMonth = 12;
    $currentYear--;
}

if($currentMonth >= 13){
    $currentMonth = 1;
    $currentYear++;
}

$daysInMonth = cal_days_in_month(
CAL_GREGORIAN,
$currentMonth,
$currentYear
);

$firstDay = date(
"N",
strtotime("$currentYear-$currentMonth-01")
);

$currentYear = isset($_GET['year'])
? (int)$_GET['year']
: (int)date("Y");

if($currentMonth < 1){
    $currentMonth = 12;
    $currentYear--;
}

if($currentMonth > 12){
    $currentMonth = 1;
    $currentYear++;
}

if(isset($_POST['quick_add_task'])){

$title = $_POST['title'];

$date = $_POST['selected_date'];

mysqli_query($conn,
"INSERT INTO tasks
(user_id, title, due_date, status)

VALUES
('$user_id',
'$title',
'$date',
'Bekliyor')");

}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Takvim</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[3].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Görev Takvimi 📅</h2>

<?php

$months = [
1=>"Ocak",
2=>"Şubat",
3=>"Mart",
4=>"Nisan",
5=>"Mayıs",
6=>"Haziran",
7=>"Temmuz",
8=>"Ağustos",
9=>"Eylül",
10=>"Ekim",
11=>"Kasım",
12=>"Aralık"
];

?>

<?php

$prevMonth = $currentMonth - 1;
$nextMonth = $currentMonth + 1;

$prevYear = $currentYear;
$nextYear = $currentYear;

if($prevMonth < 1){
    $prevMonth = 12;
    $prevYear--;
}

if($nextMonth > 12){
    $nextMonth = 1;
    $nextYear++;
}

?>

<div style="
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
">

<a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">

<button class="complete-btn">
⬅ Önceki Ay
</button>

</a>

<h2>
<?php echo $months[$currentMonth]." ".$currentYear; ?>
</h2>

<a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">

<button class="complete-btn">
Sonraki Ay ➡
</button>

</a>

</div>


<div class="calendar">

<div class="day-name">Pzt</div>
<div class="day-name">Sal</div>
<div class="day-name">Çar</div>
<div class="day-name">Per</div>
<div class="day-name">Cum</div>
<div class="day-name">Cmt</div>
<div class="day-name">Paz</div>

<?php

$tasks = mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'");

$daysInMonth = cal_days_in_month(
CAL_GREGORIAN,
$currentMonth,
$currentYear
);

$firstDay = date(
"N",
strtotime("$currentYear-$currentMonth-01")
);

for($i = 1; $i < $firstDay; $i++){

echo "<div class='empty-day'></div>";

}

for($day = 1; $day <= $daysInMonth; $day++){

$date = $currentYear."-".str_pad($currentMonth,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);

$class = "normal-day";

while($task = mysqli_fetch_assoc($tasks)){

if($task['due_date'] == $date){

if($task['status'] == "Tamamlandı"){
    $class = "completed-day";
}

elseif(
$task['due_date'] < date("Y-m-d")
&& $task['status'] != "Tamamlandı"
){
    $class = "late-day";
}

}

}

mysqli_data_seek($tasks,0);

echo "
<div class='day $class'
onclick=\"openTaskPopup('$date')\">
";

echo "<strong>$day</strong>";

while($task = mysqli_fetch_assoc($tasks)){

if($task['due_date'] == $date){

$statusColor = "#8b5cf6";

if($task['status'] == "Tamamlandı"){
    $statusColor = "#10b981";
}

elseif(
$task['due_date'] < date("Y-m-d")
&& $task['status'] != "Tamamlandı"
){
    $statusColor = "#ef4444";
}

echo "
<div style='
margin-top:8px;
padding:5px;
border-radius:8px;
font-size:12px;
background:$statusColor;
color:white;
'>
".$task['title']."
</div>
";

}

}

mysqli_data_seek($tasks,0);

echo "</div>";

}

?>

</div>

<div id="popupOverlay"
style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.4);
z-index:998;
">
</div>

<div id="taskPopup" style="
display:none;
position:fixed;
top:50%;
left:50%;
transform:translate(-50%,-50%);
background:#fff;
padding:30px;
border-radius:24px;
z-index:999;
width:350px;
box-shadow:0 15px 40px rgba(0,0,0,0.25);
">

<h3>Görev Ekle 📅</h3>

<span onclick="closePopup()"
style="
position:absolute;
top:15px;
right:20px;
cursor:pointer;
font-size:22px;
font-weight:bold;
">
✖
</span>

<form method="POST">

<input type="hidden"
name="selected_date"
id="selected_date">

<input type="text"
name="title"
placeholder="Görev Başlığı"
required>

<button type="submit"
name="quick_add_task"
style="margin-top:15px;">

Kaydet

</button>

</form>

</div>
<p class="calendar-note">Etkinlik bulunmuyor</p>
</div>

<?php include "../includes/footer.php"; ?>

</div>

<script src="../assets/js/app.js"></script>
<script>

function openTaskPopup(date){

document.getElementById("taskPopup").style.display = "block";

document.getElementById("popupOverlay").style.display = "block";

document.getElementById("selected_date").value = date;

}

function closePopup(){

document.getElementById("taskPopup").style.display = "none";

document.getElementById("popupOverlay").style.display = "none";

}

</script>
</body>
</html>