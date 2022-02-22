<style>
	body {
		min-height: 75rem;
		padding-top: 4.5rem;
	}
</style>

<noscript>
	<div class="container">
		<h4>For full functionality of this site, it is necessary to enable JavaScript. Click
			<a href="https://www.enable-javascript.com/" target="blank" rel="noreferrer noopener">here</a> for instructions on how to enable JavaScript in your web browser.
		</h4>
	</div>
</noscript>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="./">Rainbow Express&reg;</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbar">

			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="https://missionarlington.org/rainbow-express-curriculum/" target="_blank">Curriculum</a>
				</li>
			</ul>

			<ul class="navbar-nav ">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="" id="dropdownMenu" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account</a>
					<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu">
						<?php echo '<li><p class="dropdown-item disabled" style="color:#16181b"><span class="fas fa-user"></span> ' . $_SESSION['user']['name'] . '</p></li>' ?>
						<li><a class="dropdown-item" href="change_password.php"><span class="fas fa-lock"></span> Change Password</a></li>
						<hr>
						<li><a class="dropdown-item" href="logout.php"><span class="fas fa-sign-out-alt"></span> Log Out</a></li>
						<!-- <a class="dropdown-item" href=""> Dashboard</a>
						<div class="container">
							<a class="dropdown-item" href=""> Feed Posts</a>
							<a class="dropdown-item" href=""> Shops</a>
							<a class="dropdown-item disabled" href="" disabled> Advertisements (coming soon)</a>
						</div>
						<hr>
						<p class="dropdown-item disabled" style="color:#16181b"> Settings</p>
						<div class="container">
							<a class="dropdown-item" href="dashboard/settings/change_email.php"> Change Email</a>
							<a class="dropdown-item" href="dashboard/settings/change_phone.php"> Change Phone Number</a>
						</div> -->
					</ul>
				</li>
			</ul>

		</div>
	</div>
</nav>