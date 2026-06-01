<?php
session_start();
include "../config/db.php";

$message = "";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['complete'])){

    $id = $_GET['complete'];

    mysqli_query($conn,
    "UPDATE tasks 
    SET status='Tamamlandı'
    WHERE id='$id'");

    $message = "Görev tamamlandı 🎉";
}

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM tasks
    WHERE id='$id'");

    $message = "Görev silindi 🗑️";
}

if(isset($_POST['add_task'])){

    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tasks
(user_id, title, description, due_date, status)

    VALUES
('$user_id',
'$title',
'$description',
'$due_date',
'Bekliyor')";

    mysqli_query($conn,$sql);

    $message = "Görev başarıyla eklendi 🌸";
}
if(isset($_POST['upload_file'])){

    $task_id = $_POST['task_id'];

    $fileName = $_FILES['task_file']['name'];

    $tmpName = $_FILES['task_file']['tmp_name'];

    move_uploaded_file(
        $tmpName,
        "../uploads/".$fileName
    );

    mysqli_query($conn,
    "UPDATE tasks
    SET file='$fileName'
    WHERE id='$task_id'");

    $message = "Dosya yüklendi 📁";
}
$filter = "";

if(isset($_GET['filter'])){
    $filter = $_GET['filter'];
}

$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";

if($filter == "completed"){
    $sql .= " AND status='Tamamlandı'";
}

elseif($filter == "pending"){
    $sql .= " AND status='Bekliyor'";
}

$sql .= " ORDER BY id DESC";

$tasks = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Görevlerim</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<!-- SIDEBAR -->

<?php include "../includes/sidebar.php"; ?>
<script>
document.querySelectorAll('.menu a')[1].classList.add('active');
</script>
<!-- MAIN -->

<div class="main">
<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Yeni Görev Ekle 🌸</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text"
id="taskTitle"
name="title"
placeholder="Görev Başlığı"
required>


<input type="date"
name="due_date"
required>

<textarea
name="description"
placeholder="Görev açıklaması yaz..."
style="
width:100%;
padding:14px;
border-radius:12px;
border:1px solid #ddd;
margin-top:12px;
resize:none;
height:120px;
"></textarea>


<button type="submit"
name="add_task">
Görev Ekle
</button>

</form>

</div>

<div class="box" style="margin-top:30px;">

<h2>Görevlerim</h2>
<div class="task-filters">

<a href="gorevler.php">
<button class="filter-btn active-filter">
Tümü
</button>
</a>

<a href="?filter=completed">
<button class="filter-btn">
Tamamlanan
</button>
</a>

<button class="filter-btn">
Bekleyen
</button>

<a href="?filter=pending">
<button class="filter-btn">
Gecikti
</button>
</a>

</div>
<?php
if(mysqli_num_rows($tasks) == 0){
?>

<div class="empty-state">

<div class="empty-icon">
📝
</div>

<h2>
Henüz görev eklenmedi
</h2>

<p>
Yeni görev ekleyerek çalışmaya başlayabilirsin.
</p>

</div>

<?php
}
?>
<?php while($task = mysqli_fetch_assoc($tasks)) { ?>
<?php

$today = date("Y-m-d");

$diff =
(strtotime($task['due_date']) - strtotime($today))
/ (60*60*24);

if($diff < 0 && $task['status'] != "Tamamlandı"){

echo '
<div class="notification late">
⚠ Geciken Görev:
<b>'.$task['title'].'</b>
</div>';

}

elseif($diff == 0){

echo '
<div class="notification today">
📅 Bugün Son Gün:
<b>'.$task['title'].'</b>
</div>';

}

elseif($diff <= 2 && $task['status'] != "Tamamlandı"){

echo '
<div class="notification warning">
⏳ Yaklaşan Görev:
<b>'.$task['title'].'</b>
</div>';

}

?>

<?php

$statusClass = "pending";

if($task['status'] == "Tamamlandı"){
    $statusClass = "completed";
}

if(
$task['due_date'] < date("Y-m-d")
&& $task['status'] != "Tamamlandı"
){
    $statusClass = "late";
    $task['status'] = "Gecikti";
}

$progress = 45;

if($task['status'] == "Tamamlandı"){
    $progress = 100;
}

if($task['status'] == "Gecikti"){
    $progress = 70;
}
?>

<div class="task modern-task">

<div class="task-top">

<div>

<h3>
<?php echo $task['title']; ?>
</h3>
<?php if(!empty($task['description'])){ ?>

<p style="margin-top:10px;">
<?php echo $task['description']; ?>
</p>

<?php } ?>
<div class="task-date">
📅 <?php echo $task['due_date']; ?>
</div>

</div>

<div class="status <?php echo $statusClass; ?>">
<?php echo $task['status']; ?>
</div>

</div>

<div class="progress">

<div class="progress-fill"
style="width:<?php echo $progress; ?>%">
</div>

</div>

<p style="
margin-top:8px;
font-size:14px;
color:#888;
">
İlerleme: %<?php echo $progress; ?>
</p>
<?php if(!empty($task['file'])){ ?>

<a href="../uploads/<?php echo $task['file']; ?>"
target="_blank">

<button>
📂 Dosyayı Aç
</button>

</a>

<?php } ?>
<div class="task-actions">

<a href="?complete=<?php echo $task['id']; ?>">
<button class="complete-btn">
✓ Tamamlandı
</button>
</a>
<?php if($task['status'] == "Tamamlandı"){ ?>

<form method="POST"
enctype="multipart/form-data"
style="margin-top:15px;">

<input type="hidden"
name="task_id"
value="<?php echo $task['id']; ?>">

<input type="file"
name="task_file"
required>

<button type="submit"
name="upload_file">

Dosya Yükle

</button>

</form>

<?php } ?>

<a href="?delete=<?php echo $task['id']; ?>">
<button class="delete-btn">
🗑 Sil
</button>
</a>

</div>

</div>

<?php } ?>


<script src="../assets/js/app.js"></script>
</body>
</html>