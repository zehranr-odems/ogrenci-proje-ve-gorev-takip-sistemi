<?php
session_start();
include "../config/db.php";
$message = "";

$user_id = $_SESSION['user_id'];

if(isset($_POST['add_project'])){

    $title = $_POST['title'];
    $fileName = $_FILES['project_file']['name'];

$tmpName = $_FILES['project_file']['tmp_name'];

move_uploaded_file(
    $tmpName,
    "../uploads/".$fileName
);

    $description = $_POST['description'];


    $sql = "INSERT INTO projects
    (user_id, title, description, file)

    VALUES
    ('$user_id',
    '$title',
    '$description',
    '$fileName')";

    mysqli_query($conn,$sql);

    $message = "Proje eklendi 🚀";
}

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM projects
    WHERE id='$id'");

    $message = "Proje silindi 🗑️";
}

$projects = mysqli_query($conn,
"SELECT * FROM projects
WHERE user_id='$user_id'
ORDER BY id DESC");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Projeler</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<?php include "../includes/sidebar.php"; ?>

<script>
document.querySelectorAll('.menu a')[2].classList.add('active');
</script>

<div class="main">

<?php include "../includes/topbar.php"; ?>

<div class="box">

<h2>Projelerim 📁</h2>
<div class="project-grid">
<div class="box">

<h2>Yeni Proje Ekle 🚀</h2>

<form method="POST"
enctype="multipart/form-data">

<input type="text"
name="title"
placeholder="Proje Başlığı"
required>

<textarea
name="description"
placeholder="Proje açıklaması..."
style="
width:100%;
padding:14px;
border-radius:12px;
border:1px solid #ddd;
margin-top:12px;
resize:none;
height:120px;
"></textarea>

<input type="file"
name="project_file"
required>

<button type="submit"
name="add_project">

Proje Ekle

</button>

</form>

</div>

<div class="project-grid">

<?php while($project = mysqli_fetch_assoc($projects)) { ?>

<?php

$color = "pink";

if($project['progress'] >= 40){
    $color = "blue";
}

if($project['progress'] >= 80){
    $color = "purple";
}

?>

<div class="project-card <?php echo $color; ?>">

<div class="project-top">

<h3>
<?php echo $project['title']; ?>
</h3>

<span>🚀</span>

</div>

<p>
<?php echo $project['description']; ?>
</p>

<a href="?delete=<?php echo $project['id']; ?>">
<a href="../uploads/<?php echo $project['file']; ?>"
target="_blank">

<button class="complete-btn">

📂 Projeyi Aç

</button>

<a href="?delete=<?php echo $project['id']; ?>">

<button style="
margin-top:15px;
background:white;
color:#111;
">

Sil

</button>

</a>

</div>

<?php } ?>

</div>


</div>
</div>

<?php include "../includes/footer.php"; ?>

</div>

<script src="../assets/js/app.js"></script>

</body>
</html>