<?php require 'config/condb.php'; ?>

<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <?php
                $sql = "SELECT * FROM tbl_start_date ORDER BY id DESC LIMIT 1";
                $stmt = $conn->query($sql);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="text-muted">Copyright &copy; Green Tech Agro (V5.0) &middot; Last Backup: <span style="color:blue;"><?=date('d-m-Y H:i', strtotime($row['start_date']));?></span></div>
            <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>

<!-- Js -->
<script src="assets/dist/js/scripts.js"></script>
<!-- JS Bootstrap 5.3.0 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->    
<script type="text/javascript" src="assets/plugins/DataTables/datatables.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- Summernote -->
<script src="assets/plugins/summernote/summernote-lite.min.js"></script>
