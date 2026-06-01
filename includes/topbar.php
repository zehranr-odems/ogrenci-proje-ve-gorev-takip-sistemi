<div class="topbar searchInput">


<div class="search-box">

<input type="text"
id="globalSearch"
placeholder="Görev, proje veya kullanıcı ara...">

</div>
<div class="top-actions">

<button class="mode-btn" id="darkModeToggle">
🌙
</button>

<a href="/görev_proje/dashboard/bildirimler.php" class="icon-btn">
🔔
</a>

<a href="/görev_proje/dashboard/profil.php" class="profile-btn">
👤
<span><?php echo $_SESSION['username']; ?></span>
</a>

</div>

</div>
<script src="../assets/js/app.js"></script>
<script src="../assets/js/search.js"></script>