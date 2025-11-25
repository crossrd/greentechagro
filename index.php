<?php
// session_start();
// $status = $_SESSION["status"];
// $userID = $_SESSION["userID"];
$status = 0;
$userID = 4;

require 'config/condb.php';
include 'function.php';

checkAndRedirect($status, $userID, ['0', '1', '2', '3'], []);

include 'header.php';
include 'menu.php';

logUserPageAccess($userID, basename(__FILE__, '.php'), '', '', $conn);

?>

<body class="sb-nav-fixed">
    <div id="layoutSidenav_content">
        <main>
			<div class="container p-3">
				<div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card" style="width:400px">
                            <img class="card-img-top" src="img/no_image.png" alt="Card image">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text.</p>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</main>
		<?php 
		include 'footer.php'; 
		$conn = null;
		?>
	</div>
</body>
</html> 
