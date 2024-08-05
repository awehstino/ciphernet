
<!DOCTYPE html>
<html>
<head>
	<title>Ciphernet</title>
	<link rel="stylesheet" type="text/css" href="<?= base_url('css/slideform.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" >

</head>
<body>

	<div class="main">
	<?php 
$status = session()->get('status');
$statusTime = session()->get('status_time');
$displayTime = 5; // Time in seconds to display the message

if ($status && $statusTime && (time() - $statusTime) <= $displayTime): 
?>
   
        <div class="status">
            <?= $status ?>
        </div>
   
<?php 
    // Unset the status after it has been displayed
    session()->remove('status');
    session()->remove('status_time');
endif; 
?> 
		
		<input type="checkbox" id="chk" aria-hidden="true">
		

			<div class="signup">
				<form action="<?= base_url('/admin_signin') ?>" method="post" >
					<label for="chk" aria-hidden="true">Sign up</label>
					<input type="text" name="fullname" placeholder="fullname" required="">
					
					<input type="password" name="password" placeholder="Password" required="">
					<button type="submit">Sign up</button>
				</form>
			</div>

			<div class="login">
				<form action="<?= base_url('/admin_login') ?>" method="post" >
					<label for="chk" aria-hidden="true">Login</label>
					<input type="text" name="username" placeholder="username" required="">
					<input type="password" name="password" placeholder="Password" required="">
					<button type="submit">Login</button>
				</form>
			</div>
	</div>



</body>


</html>

